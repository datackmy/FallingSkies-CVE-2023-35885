<?php
namespace App\Service;
use Defuse\Crypto\Crypto as DefuseCrypto;
class Crypto
{
    public static function encrypt(string $text)
    {
        goto Fa81b;
        Fa81b:
        $secret = self::getSecret();
        goto c0547;
        c0547:
        $encryptedText = DefuseCrypto::encryptWithPassword($text, $secret, false);
        goto Cba39;
        Cba39:
        return $encryptedText;
        goto cd7ec;
        cd7ec:
    }
    public static function decrypt(string $encryptedText)
    {
        goto Cbbd4;
        E6cdf:
        $decryptedText = DefuseCrypto::decryptWithPassword($encryptedText, $secret, false);
        goto ee5ac;
        ee5ac:
        return $decryptedText;
        goto E02cf;
        Cbbd4:
        $secret = self::getSecret();
        goto E6cdf;
        E02cf:
    }
    private static function getSecret(): ?string
    {
        $secret = "a9cb07f5fe00bde78b322609c57533ae";
        return $secret;
    }
}