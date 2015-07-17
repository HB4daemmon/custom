<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$password    = '123456';
$hash = 'c8b8b0589072d0c2b127ef7ea642f9af';
echo getHash($password,'JLkJTJqFjsLEL8tlcM0lXf2hO0VhuiUQ').'<br>'; //hash 加密
echo validateHash($password,$hash); //hash 加密验证
function getRandomString($len, $chars=null)
{
    if (is_null($chars)) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}
function getHash($password, $salt=false)
{
    if (is_integer($salt)) {
        $salt = getRandomString($salt);
    }
    return $salt===false ? md5($password) : md5($salt.$password).':'.$salt;
}
/*
 * 验证密码
 @param string $password
* @param string $hash
* @return bool
 */
function validateHash($password,$hash)
{
    $hashArr = explode(':', $hash);
    switch (count($hashArr)) {
        case 1:
            return getHash($password) === $hash;
        case 2:
            return getHash($hashArr[1] . $password) === $hashArr[0];
    }
    return 'Invalid hash.';
}
?>