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
namespace NetDeviceLib\Device;

use NetDeviceLib\Core\InstanceConfigTrait;
use NetDeviceLib\Error;
use NetDeviceLib\Net;

class BaseDevice {

	use InstanceConfigTrait;

/**
 * Default configuration for the device.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'client'=> [
			'className' => '\NetDeviceLib\Net\Ssh\Client',
			'ssh' => [
				'host' => null,
				'port' => 22,
				'timeout' => 15,
				'methods'=>[
					'kex'=>'diffie-hellman-group1-sha1'
				],
				'authType'=>'password',
				'credentials'=>[
					'username'=>'',
					'password'=>''
				]
			],
			'eol'=>"\n",
			'readTimeout'=>2,
			'prompt'=>[
				'command'  => '$',
			],
			'commands'=>[
				'onConnect'=>[],
				'onDisconnect'=>[
					'quit'
				]
			],
			'tmpPath'=>'/tmp'
		]
	];

/**
 * Client Object
 * 
 */
	public $Client;

/**
 * Config Object
 * 
 */
	public $Config;

/**
 * __construct method
 *
 */
	public function __construct($config = []) {
		$this->config($config);

		$className = $this->config('client.className'); 
		unset( $this->_config['client']['className'] );

		//Construct Client
		switch( key($this->config('client')) ) {
			case 'ssh':
				$clientClass = $className;
				break;
			case 'telnet':
				$clientClass = '\NetDeviceLib\Net\Telnet\Client';
				break;
			default:
				throw new \Exception('Device: Invalid client type specified in configuration, use ssh or telnet');
		}

		$this->Client = new $clientClass($this->config('client'));

		//Construct Config
		$this->Config = new \NetDeviceLib\Config\BaseConfig( $this );
	
	}

	public function connect() {
		
		$this->Client->connect();

	}



}