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
namespace NetDeviceLib\Vendor\VyOS\VyOS\Config;

use NetDeviceLib\Config\ConfigInterface;
use NetDeviceLib\Config\BaseConfig;

class Config extends BaseConfig implements ConfigInterface {

	protected $_device;

	function __construct( $Device ) {
		parent::__construct($Device);
	}

	public function read() {
		$this->_device->Client->execute('set terminal length 0');

		return $this->_device->Client->execute('show configuration');

	}

	public function update( $config ) {
		//Danger!
		$this->_device->execute( 'configure' );
		$this->_device->execute( $config );
	}

	public function save( ) {
		$this->_device->Client->execute('configure');
		$this->_device->Client->execute('commit');
		$results = $this->_device->Client->execute('save');
		echo $results;
		exit;
		return;
	}

	public function erase() {
		throw new \Exception('Not Implemented Yet');
	}

}