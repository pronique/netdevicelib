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
namespace NetDeviceLib\Config;


class BaseConfig implements ConfigInterface {

	protected $_device;

	function __construct( $Device ) {
		$this->_device = $Device;
	}

	function read() {
		//read Device config 
		throw new \Exception('Classes that extend BaseConfig MUST override read() method');
	}

	function update( $config ) {
		throw new \Exception('Classes that extend BaseConfig MUST override update() method');
	}

	public function save( ) {
		return;
	}

	function erase() {
		//erase Device config
	}

}