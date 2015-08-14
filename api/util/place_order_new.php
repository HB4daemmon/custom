<?php
//------------------------------------------------------------------------------------------
/* 下单
 *
 * @param:GET/POST 
 *   $goods_list      商品列表
 *   $amount          商品总额
 *   $name            收货人
 *   $tel             收货电话
 *   $address         收货地址
 *   $remark          用户留言
 *   $mobile          用户手机号
 *   $freight         运费
 *   $redpaper_id     红包ID
 *   $payment         支付方式
 *   $shipping_time   配送时间
 *   $device_id       设备号
 *
 * @return:JSON(data,success,errorcode)
 *   null/array/url   按不同的支付方式返回下单成功信息
 *
 * by Serige 2015-4-23
 */
//------------------------------------------------------------------------------------------

include_once("../../weixin/conn.php");
include_once("errcode.php");
include_once("../../magento/custom/api/wx/wx_wap.php");

//print_r($_REQUEST);exit;
//report(1000,"服务器更新中，目前无法下单");
define("IN_ECTOUCH","1");
//$goods_id = intval($_REQUEST['goods_id']);
//$num = intval($_REQUEST['num']);
$goods_list = json_decode($_REQUEST['goods_list'],true); //商品列表
$amount = doubleval($_REQUEST['amount']); //商品总额
$consignee = has_emoji(trim($_REQUEST['name'])); //收货人
$tel = trim($_REQUEST['tel']); //收货电话
$address = has_emoji(trim($_REQUEST['address'])); //收货地址
$postscript = addslashes(trim($_REQUEST['remark'])); //用户留言
$mobile = trim($_REQUEST['mobile']); //用户手机号
$freight = doubleval($_REQUEST['freight']); //运费
$redpaper_id = intval($_REQUEST['redpaper_id']); //红包ID
$shipping_time = trim($_REQUEST['shipping_time']); //送货时间
$payment = trim($_REQUEST['payment']);
$device_id = trim($_REQUEST['device_id']);
$city = trim($_REQUEST['city']);
$district = trim($_REQUEST['district']);
$area = trim($_REQUEST['area']);
$shipping_type = trim($_REQUEST['shipping_type']);
$scan = trim($_REQUEST['scan']);
//$attach = trim($_REQUEST['attach']);

if(!$goods_list || !$amount || !$consignee || !$tel || !$address || !$city || !$district || !$area || !$shipping_time || !$shipping_type)
{
    report(1001,"信息填写不完整");
    exit;
}

if($amount < 10 and $shipping_type!='现场购买'){
    report(1001,"对不起，您的订单不满10元，无法下单");
    exit;
}

$goods_id_str = '';
$goods_count_arr = array();
foreach ($goods_list as $v)
{
    $goods_id_str .= ','.$v[0];
    $goods_count_arr[$v[0]]=$v[1];
}
$goods_id_str = trim($goods_id_str,',');
//获取商品信息
$goods = $goods_arr = array();
$count_1yuan = 0;
if($result = mysql_query("SELECT * FROM ecs_goods WHERE goods_id IN (" . $goods_id_str . ")"))
{
    while($row = mysql_fetch_array($result))
    {
        if(!$row)
        {
            report(1003,"该商品不存在或已下架");
            exit;
        }
        $goods_amount += $row['shop_price']*$goods_count_arr[$row['goods_id']];
        $goods_body .= $row['goods_name']." ";
        $goods[]=$row;
        $goods_arr[] = $row['goods_name'].'*'.$goods_count_arr[$row['goods_id']];

        if($row['cat_id'] == '145' || $row['cat_id'] == '153')
        {
            $count_1yuan += $goods_count_arr[$row['goods_id']];
        }
    }
    if($count_1yuan > 1)
    {
        report(1026,"1元购商品每次只能选择1件");
        exit;
    }
    elseif($count_1yuan == 1)
    {
        $is_1yuan = 1;
    }
}
else
{
    report(1000,"非法请求");
    exit;
}

if(!$payment && $is_1yuan)
{
    report(1026,"1元购商品只能在线支付");
    exit;
}

if(!$payment && $shipping_type!="配送上门")
{
    report(1026,"自提与现场购只能在线支付");
    exit;
}

$discount = 0;

//获取用户信息
if($mobile)
{
    $sql = "SELECT *".
        " FROM ecs_users AS u LEFT JOIN ecs_user_address AS ua ON ua.user_id = u.user_id ".
        " WHERE u.mobile_phone='$mobile' LIMIT 1";
    $referer = "2015APP v2.1";
}
else
{
    report(1001,"没有获取到登录信息");
    exit;
}

if($result = mysql_query($sql))
{
    $user = mysql_fetch_array($result);
    if(!$user)
    {
        report(1004,"用户不存在");
        exit;
    }
    //$goods_amount = $goods['shop_price']*$num;
    /*	$city = $city?$city:$user['city_name'];
        if($city=="上海市")
        {
            $city_id = 1;
        }
        else
        {
            $city_id = 0;
        }

        $address_ok = 0;
        foreach($area_list[$city_id] as $v)
        {
            if(strstr($address, $v))
            {
                $address_ok = 1;
                break;
            }
        }
        */
    if($city=="上海市")
    {
        $city_id = 1;
        $city = "上海市";
    }
    else
    {
        $city_id = 0;
        $city = "南京市";
    }
    if(!in_array($area,$area_list_new[$city_id][$district]))
    {
        report(1026,"您的收货地址不在配送范围内");
        exit;
    }

    $shipping_date = get_shipping_date($shipping_time);
    if(!$shipping_date)
    {
        report(1026,"您选择的收货时间暂时无法配送");
        exit;
    }

    //获取红包信息
    if($redpaper_id)
    {
        $order_num = mysql_fetch_array(mysql_query("SELECT count(*) AS count FROM ecs_order_info WHERE user_id='".$user['user_id']."'"));
        $now = gmtime();
        $sql = "SELECT * ".
            " FROM ecs_user_redpaper AS a LEFT JOIN ecs_redpaper AS b ON a.red_id=b.red_id ".
            " WHERE a.mobile = '$mobile' AND a.id='$redpaper_id' AND a.order_sn = '' AND b.is_show = 1 AND a.overtime>$now LIMIT 1 ";
        $redpaper = mysql_fetch_array(mysql_query($sql));
        if($redpaper)
        {
            if($redpaper['isfirst']==1 && $order_num['count'] > 0)
            {
                report(1022,"该优惠券仅限首单使用");
                exit;
            }
            if($redpaper['paytype']==1 && !$payment)
            {
                report(1022,"该优惠券仅限在线支付使用");
                exit;
            }
            elseif($redpaper['paytype']==2 && $payment)
            {
                report(1022,"该优惠券仅限货到付款使用");
                exit;
            }
            $red = $redpaper['amount']>$goods_amount?$goods_amount:$redpaper['amount'];
            $discount += $red;
        }
        else
        {
            report(1022,"该优惠券不存在或已过期");
            exit;
        }
    }


    if($goods_amount != $amount)
    {
        report(1007,"待支付的金额有误");
        exit;
    }

    if($count_1yuan>0)
    {

        if($device_id)
        {
            $devsql = " OR device_id = '$device_id' ";
        }

        $sql = "SELECT * FROM ecs_order_info WHERE user_id = '".$user['user_id']."' AND is_1yuan = '1' $devsql ORDER BY add_time DESC LIMIT 1";
        $od = mysql_fetch_array(mysql_query($sql));

        if($od && local_date("Y-m-d",$od['add_time']) == local_date("Y-m-d",$now))
        {
            report(1026,"1元购活动每日只能参加1次");
            exit;
        }
    }

    //生成订单
    try
    {
        $user_id = $user['user_id'];

        //若用户没有个人信息则添加
        if(!$user['consignee'])
        {
            mysql_query("UPDATE ecs_user_address SET address='$address', consignee='$consignee', tel='$tel' WHERE user_id='$user_id' LIMIT 1");
        }

        //更新用户推荐人
        if($code)
        {
            mysql_query("UPDATE ecs_users SET couponcode='$code' WHERE user_id='$user_id' LIMIT 1");
        }

        if($device_id)
        {
            $devsql = " OR device_id = '$device_id' ";
        }

        $order_num = mysql_fetch_array(mysql_query("SELECT count(*) AS count FROM ecs_order_info WHERE user_id='".$user['user_id']."' $devsql "));

        //更新地址列表
        $new_address = mysql_fetch_array(mysql_query("SELECT * FROM ecs_user_address_list WHERE user_id = '$user_id' AND address = '$address' LIMIT 1"));
        if($new_address)
        {
            if(intval($order_num['count'])>0)
                $address = str_replace("现场购买","",$address);
            mysql_query("UPDATE ecs_user_address_list SET address='$address', city = '$city', district = '$district', area = '$area', name='$consignee', tel='$tel', remark='$postscript', dateline = '$now' WHERE id = '".$new_address['id']."' AND user_id = '$user_id' ");
        }
        else
        {
            if(intval($order_num['count'])>0)
                $address = str_replace("现场购买","",$address);
            mysql_query("INSERT INTO ecs_user_address_list ( user_id, name, tel, city, district, area, address, remark, dateline ) VALUES ('$user_id', '$consignee', '$tel', '$city', '$district', '$area', '$address', '$postscript', '$now')");
        }

        //生成订单
        //现场购买的产品替换
        if(isset($scan) and $scan=='Y'){
            $new_goods_list = array();
            $sku_list = array("550"=>"576","491"=>"578","535"=>"577","525"=>"579");
            foreach($goods_list as $g){
                if(array_key_exists($g[0],$sku_list)){
                    array_push($new_goods_list,array($sku_list[$g[0]],$g[1]));
                }else{
                    array_push($new_goods_list,array($g[0],$g[1]));
                }
            }
            $goods_list = $new_goods_list;
            $goods_count_arr = array();
            $goods_id_str = '';
            foreach ($goods_list as $v)
            {
                if($v[1] > 0){
                    $goods_count_arr[$v[0]]=$v[1];
                    $goods_id_str .= ','.$v[0];
                }
            }
            $goods_id_str = trim($goods_id_str,',');

            //获取商品信息
            $goods = array();
            if($result = mysql_query("SELECT * FROM ecs_goods WHERE goods_id IN (" . $goods_id_str . ")"))
            {
                while($row = mysql_fetch_array($result))
                {
                    if(!$row)
                    {
                        report(1003,"该商品不存在或已下架");
                        exit;
                    }
                    array_push($goods,$row);
                }
            }
            else
            {
                report(1000,"非法请求");
                exit;
            }
        }

        $order_sn = get_order_sn();
        $add_time = gmtime();

        if((intval($order_num['count']) < 1 && $payment != '') || $amount>=$full || strstr($address,"现场购买") || $shipping_type == "现场购买")
        {
            $freight = 0;
        }
        else
        {
            $freight = 6;
        }
        $amount += $freight;


        //$tt = local_date("w",$add_time+86400) == '0'?$add_time+86400*2:$add_time+86400;
        $to_buyer = $shipping_time;

        if(!$payment)
        {
            $pay_id = "6";
            $pay_name = "货到付款";
        }
        else
        {
            $pay_id = "-1";
            $pay_name = "在线支付";
        }
        $attach = $order_sn;
        mysql_query(" INSERT INTO ecs_order_info ( " .
            " order_sn, user_id, consignee, address, tel, pay_id, pay_name, postscript, ".
            " how_oos, goods_amount, shipping_fee, order_amount, referer, to_buyer, add_time, couponcode, redpaper_id, redpaper_amount, discount, is_1yuan, device_id, city_name, district_name, area_name, shipping_type, shipping_date, attach) ".
            " VALUES ( ".
            " '$order_sn', '$user_id', '$consignee', '$address', '$tel', '$pay_id', '$pay_name', '$postscript', ".
            " '待发货', '$goods_amount', '$freight', '".($amount-$discount)."', '$referer', '$to_buyer', '$add_time', '$code', '$redpaper_id', '".$redpaper['amount']."', '$discount', '$is_1yuan', '$device_id', '$city', '$district', '$area', '$shipping_type', '$shipping_date', '$attach')");
        $order_id = mysql_insert_id();

        foreach($goods as $v)
        {
            mysql_query(" INSERT INTO ecs_order_goods ( " .
                " order_id, goods_id, goods_name, goods_sn, goods_number, market_price, goods_price, is_real ) ".
                " VALUES ( ".
                " '$order_id', '".$v['goods_id']."', '".$v['goods_name']."', '".$v['goods_sn']."', '".$goods_count_arr[$v['goods_id']]."', '".$v['goods_sn']."', '".$v['shop_price']."', '1' )");

        }

        //生成支付清单
        mysql_query("INSERT INTO ecs_pay_log (log_id, order_id, order_amount, order_type, is_paid)".
            " VALUES  ('$order_id', '$order_id', '$order_amount', '0', '0') ");
        $order = mysql_fetch_array(mysql_query("SELECT * FROM ecs_order_info WHERE order_id = '$order_id' LIMIT 1"));

        //使用红包
        if($redpaper_id)
        {
            mysql_query(" UPDATE ecs_user_redpaper SET order_sn = '$order_sn', usetime = '$add_time' WHERE id='$redpaper_id' ");
        }

        //邮件通知
        if($order && !in_array($user_id,array(69866,69971,69964)))
        {
            $str = file_get_contents("http://meiguoyouxian.com/shop/send_mail.php?type=order_pay&order_id=".$order_id);
        }


        if(!$payment)
        {
            report(0);
            exit;
        }
        else
        {
            if($payment == "alipay")
            {
                //if($user_id == 69971)
                {
                    $pay_id = -1;
                    $output=array(
                        "out_trade_no"=>$order['order_sn'].'_'.$order['order_id'],
                        "subject"=>$goods[0]['goods_name']."...",
                        "body"=>implode(' | ',$goods_arr),
                        "total_fee"=>$order['order_amount'],
                        "notify_url"=>"http://meiguoyouxian.com/shop/mobile/notify/alipay/notify_url.php",
                        "show_url"=>"http://meiguoyouxian.com/shop/mobile/app_goods.php?id=".$goods[0]['goods_id'],
                    );
                    report(0,$output);
                    exit;
                }
            }
            elseif($payment == 'weixin'){
                $pay_id = 4;
                $order = getPrepayId($amount,$order_id,$attach);
                $pay_str = pay($order);
                if($pay_str['success'] == 0){
                    report(10052,$pay_str['return_msg']);
                }else{
                    report(0,$pay_str);
                    //$result = array("data"=>$order,"success"=>0,"errorcode"=>$errorcode);
                    //echo json_encode($result);
                }
                exit;
            }
            elseif($payment == "alipay_wap")
            {
                $pay_id = 5;
                $payment = mysql_fetch_array(mysql_query("SELECT * FROM ecs_touch_payment WHERE pay_id = '$pay_id' AND enabled = 1"));
                include_once($payment['pay_code'] . '.php');
                $pay_obj    = new $payment['pay_code'];
                $output = $pay_obj->get_code_url($order, unserialize_config($payment['pay_config']));
                report(0,$output);
                exit;
            }
            else
            {
                report(1005,"非法的支付方式");
                exit;
            }
        }

    }
    catch(Exception $e)
    {
        report(1000,"非法请求");
        exit;
    }

}
else
{
    report(1000,"非法请求");
    exit;
}

?>