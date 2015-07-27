<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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