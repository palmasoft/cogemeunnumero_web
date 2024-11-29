<?php

/**
 * OneAsymmetricKey
 *
 * See https://tools.ietf.org/html/rfc5958
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps;

use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1;
/**
 * OneAsymmetricKey
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class OneAsymmetricKey
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['version' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER, 'mapping' => ['v1', 'v2']], 'privateKeyAlgorithm' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\AlgorithmIdentifier::MAP, 'privateKey' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\PrivateKey::MAP, 'attributes' => ['constant' => 0, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Attributes::MAP, 'publicKey' => ['constant' => 1, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\PublicKey::MAP]];
}