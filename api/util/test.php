<?php
define('IN_ECTOUCH',TRUE);
require_once dirname(__FILE__)."/../../../../shop/mobile/include/init.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/lib_payment.php";
require_once dirname(__FILE__)."/../../../../shop/mobile/include/lib_order.php";

$out_trade_no = '57205';

order_paid($out_trade_no, 2);

echo 'success';

?>