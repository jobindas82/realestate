<?php

namespace App\Essentials;

class UriEncode
{
    const METHOD = 'AES-256-CBC';
    const SECRET = 'ufet74j5674f7hw874f7842jsjdhf7j98r7j3wjtufw8';
    const IV = '3475c7j5292q8978j7q8482m990q8kj7d3e7j83q08dwtf';

    /**
     * simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     *
     * @param string $action: can be 'encrypt' or 'decrypt'
     * @param string $string: string to encrypt or decrypt
     *
     * @return string
     */
    public static function encrypt($string)
    {
        $output = 0;
        // hash
        $key = hash('sha256', self::SECRET);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', self::IV), 0, 16);

        $output = openssl_encrypt($string, self::METHOD, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    public static function decrypt($string)
    {
        $output = 0;
        // hash
        $key = hash('sha256', self::SECRET);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', self::IV), 0, 16);
        $output = openssl_decrypt(base64_decode($string), self::METHOD, $key, 0, $iv);

        return $output;
    }
}
