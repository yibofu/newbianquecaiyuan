<?php
namespace Common\Org;
class Des {
 
    private static $_instance = NULL;
    /**
     * @return DES
     */
    public static function share() {
        if (is_null(self::$_instance)) {
            self::$_instance = new Des();
        }
        return self::$_instance;
    }
 
    /**
     * 加密
     * @param string $str 要处理的字符串
     * @param string $key 加密Key，为8个字节长度
     * @return string
     */
    public function encode($str, $key='Yc45h#.9') {
        if(!is_string($str)){
            $str = json_encode($str);
        }
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $str = $this->pkcs5Pad($str, $size);
        $val = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_CBC, $key);
        $ret = base64_encode($val);
        return $ret;
    }
 
    /**
     * 解密
     * @param string $str 要处理的字符串
     * @param string $key 解密Key，为8个字节长度
     * @return string
     */
    public function decode($str, $key='Yc45h#.9') {
        $strBin = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $strBin, MCRYPT_MODE_CBC, $key);
        $str = $this->pkcs5Unpad($str);
        return $str;
    }
 
    public function hex2bin($hexData) {
        $binData = "";
        for ($i = 0; $i < strlen($hexData); $i += 2) {
            $binData .= chr(hexdec(substr($hexData, $i, 2)));
        }
        return $binData;
    }
 
    public function pkcs5Pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
 
    public function pkcs5Unpad($text) {
        $pad = ord($text {strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, - 1 * $pad);
    }
}