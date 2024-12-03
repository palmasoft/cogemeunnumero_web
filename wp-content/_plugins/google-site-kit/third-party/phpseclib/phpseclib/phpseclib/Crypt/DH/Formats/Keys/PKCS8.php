<?php

/**
 * PKCS#8 Formatted DH Key Handler
 *
 * PHP version 5
 *
 * Processes keys with the following headers:
 *
 * -----BEGIN ENCRYPTED PRIVATE KEY-----
 * -----BEGIN PRIVATE KEY-----
 * -----BEGIN PUBLIC KEY-----
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Google\Site_Kit_Dependencies\phpseclib3\Crypt\DH\Formats\Keys;

use Google\Site_Kit_Dependencies\phpseclib3\Crypt\Common\Formats\Keys\PKCS8 as Progenitor;
use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1;
use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps;
use Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger;
/**
 * PKCS#8 Formatted DH Key Handler
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class PKCS8 extends \Google\Site_Kit_Dependencies\phpseclib3\Crypt\Common\Formats\Keys\PKCS8
{
    /**
     * OID Name
     *
     * @var string
     */
    const OID_NAME = 'dhKeyAgreement';
    /**
     * OID Value
     *
     * @var string
     */
    const OID_VALUE = '1.2.840.113549.1.3.1';
    /**
     * Child OIDs loaded
     *
     * @var bool
     */
    protected static $childOIDsLoaded = \false;
    /**
     * Break a public or private key down into its constituent components
     *
     * @param string $key
     * @param string $password optional
     * @return array
     */
    public static function load($key, $password = '')
    {
        $key = parent::load($key, $password);
        $type = isset($key['privateKey']) ? 'privateKey' : 'publicKey';
        $decoded = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::decodeBER($key[$type . 'Algorithm']['parameters']->element);
        if (empty($decoded)) {
            throw new \RuntimeException('Unable to decode BER of parameters');
        }
        $components = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::asn1map($decoded[0], \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DHParameter::MAP);
        if (!\is_array($components)) {
            throw new \RuntimeException('Unable to perform ASN1 mapping on parameters');
        }
        $decoded = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::decodeBER($key[$type]);
        switch (\true) {
            case !isset($decoded):
            case !isset($decoded[0]['content']):
            case !$decoded[0]['content'] instanceof \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger:
                throw new \RuntimeException('Unable to decode BER of parameters');
        }
        $components[$type] = $decoded[0]['content'];
        return $components;
    }
    /**
     * Convert a private key to the appropriate format.
     *
     * @param BigInteger $prime
     * @param BigInteger $base
     * @param BigInteger $privateKey
     * @param BigInteger $publicKey
     * @param string $password optional
     * @param array $options optional
     * @return string
     */
    public static function savePrivateKey(\Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $prime, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $base, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $privateKey, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $publicKey, $password = '', array $options = [])
    {
        $params = ['prime' => $prime, 'base' => $base];
        $params = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($params, \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DHParameter::MAP);
        $params = new \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Element($params);
        $key = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($privateKey, ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER]);
        return self::wrapPrivateKey($key, [], $params, $password, null, '', $options);
    }
    /**
     * Convert a public key to the appropriate format
     *
     * @param BigInteger $prime
     * @param BigInteger $base
     * @param BigInteger $publicKey
     * @param array $options optional
     * @return string
     */
    public static function savePublicKey(\Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $prime, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $base, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $publicKey, array $options = [])
    {
        $params = ['prime' => $prime, 'base' => $base];
        $params = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($params, \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DHParameter::MAP);
        $params = new \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Element($params);
        $key = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($publicKey, ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER]);
        return self::wrapPublicKey($key, $params);
    }
}