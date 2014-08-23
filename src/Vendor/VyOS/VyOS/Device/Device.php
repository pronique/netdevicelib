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
namespace NetDeviceLib\Vendor\VyOS\VyOS\Device;

use NetDeviceLib\Core\InstanceConfigTrait;
use NetDeviceLib\Device\BaseDevice;
use NetDeviceLib\Vendor\VyOS\VyOS\Config\Config;
use DominionEnterprises\ColumnParser\MultispacedHeadersParser;

/**
 * Vendor Specific Implementation to interact with VyOS Virtual Router
 *
 * 
 */
class Device extends BaseDevice {

/**
 * Default configuration for the device.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'client'=> [
			'className' => '\NetDeviceLib\Net\Ssh\PhpseclibClient',
			'ssh' => [
				'host' => null,
				'port' => 22,
				'timeout' => 15,
				'authType'=>'password',
				'credentials'=>[
					'username'=>'',
					'password'=>''
				]
			],
			'prompt'=>[
				'command'  => '$',
			]
		]
	];

/**
 * Instance of Net\<Protocol>\Client class
 *
 * @var NetDeviceLib\Net\<Protocol>\Client
 */
	public $Client;

/**
 * Instance of RouterOS Config class
 *
 * @var \NetDeviceLib\Vendor\VyOS\VyOS\Config\Config
 */
	public $Config;

	public function __construct( $config = [] ) {

		parent::__construct( $config );

		$this->Config = new Config( $this );

	}

/**
 * Connect to Device
 */
	public function connect() {
		parent::connect();
		
	}


/**
 * Disconnect from Device
 */
	public function disconnect() {
		//$this->Client->disconnect();
	}

/**
 * Returns Device's Name
 *
 * @return String;
 */
	public function getName() {
		$response = $this->Client->execute('show host name');
		return $response;
	}

/**
 * Returns Device's Software Version Information
 *
 * @return String;
 */
	public function getVersion() {
		$response = $this->Client->execute('show version');
		preg_match('/Version:\s+VyOS\s([0-9\.]+)/', $response, $matches );
		$version = $matches[1];
		return $version;
	}

/**
 * Returns Device's Uptime
 *
 * @return String;
 */
	public function getUptime() {
		$response = $this->Client->execute('show system uptime');
		return $response;
	}


/**
 * Returns Device's System Resource Information
 *
 * @return Array;
 */
	public function getInfo() {
		$response = $this->Client->execute('show version');
		return $response;
	}

/**
 * Reboot the device
 *
 * @return null;
 */
	public function reboot() {
		$response = $this->Client->execute('reboot now');
		return $response;
	}

}