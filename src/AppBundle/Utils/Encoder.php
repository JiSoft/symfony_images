<?php

namespace AppBundle\Utils;

/**
 * Class Encoder
 * Encodes/decodes integer IDs
 * @author Igor Cherkashin aka JiSoft <jisoft.dn@gmail.com>
 * @author  Simon Franz
 * @author Kevin van Zonneveld
 */
class Encoder
{
    private $index = 'jnGMNcVJLdep035ly8awAiOPYbmoDFHKWhzQrsTUR6ux124IXkv9Eqt7BfCSZg';

    /**
     * Encode int ID to string
     * @param int $in  ID
     * @param int $len
     * @return string
     * @author  Kevin van Zonneveld &lt;kevin@vanzonneveld.net>
     * @author  Simon Franz
     * @author  Deadfish
     * @author  SK83RJOSH
     * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
     * @link    http://kevin.vanzonneveld.net/*
     */
    public function encodeId($in, $len = 0)
    {
        $out   =   '';
        $base  = strlen($this->index);

        $len = (int) $len;
        if ($len>0) {
            $len--;
            if ($len > 0) {
                $in += pow($base, $len);
            }
        }

        for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($this->index, $a, 1);
            $in  = $in - ($a * $bcp);
        }

        return $out;
    }

    /**
     * Decode string to int
     * @param $in
     * @param int $len
     * @return number|string
     * @author  Kevin van Zonneveld &lt;kevin@vanzonneveld.net>
     * @author  Simon Franz
     * @author  Deadfish
     * @author  SK83RJOSH
     * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
     * @link    http://kevin.vanzonneveld.net/
     */
    public function decodeId($in, $len=0)
    {
        $out   =   '';
        $base  = strlen($this->index);
        $size = strlen($in) - 1;

        for ($t = $size; $t >= 0; $t--) {
            $bcp = bcpow($base, $size - $t);
            $out = $out + strpos($this->index, substr($in, $t, 1)) * $bcp;
        }

        $len = (int) $len;
        if ($len>0) {
            $len--;

            if ($len > 0) {
                $out -= pow($base, $len);
            }
        }
        return $out;
    }

}