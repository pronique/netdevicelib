<?php
/**
 * NetDeviceLib
 * Copyright (c) PRONIQUE Software (http://pronique.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) PRONIQUE Software (http://pronique.com)
 * @link          http://pronique.com NetDeviceLib Project
 * @since         0.5.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace NetDevLib\Net\Ssh\Pki;

/**
 * SSH Key Generator
 *
 */
class KeyGenerator {

/**
 * Contains all the key types
 *
 * @var array
 */
	protected $_encryptMethods = array(
		// @codingStandardsIgnoreStart
		'SSH1_RSA' => 'foo',
		'SSH2_RSA' => 'foo',
		'SSH2_DSA' => 'foo',
		'ECDSA' => 'foo',

		// @codingStandardsIgnoreEnd
	);
}