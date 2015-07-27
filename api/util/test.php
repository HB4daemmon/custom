<?php
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

echo validateHash('654321','c33367701511b4f6020ec61ded352059');

?>