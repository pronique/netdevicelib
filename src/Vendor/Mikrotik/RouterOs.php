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
namespace NetDeviceLib\Vendor\Mikrotik;

use NetDeviceLib\Error;
use NetDeviceLib\Utility\Hash;
use NetDeviceLib\Core\InstanceConfigTrait;


class RouterOs {

	use InstanceConfigTrait;

/**
 * Default configuration for the client.
 *
 * @var array
 */
	protected $_defaultConfig = [
    'commandDriver'=>'Ssh',
    'statDriver'=>'Snmp',
		'host' => null,
		'port' => 22,
		'user'=> null,
		'pass'=> null
	];

	protected $_commandDriver;
	protected $_statDriver;
 

	public function __construct($config = []) {
		$this->config($config);

		//Load the command driver
		$driver = "\\NetDeviceLib\\Network\\".ucfirst($this->config('commandDriver') )."\\Client"; 
		$this->_commandDriver = new $driver( $this->config() );

		//Load the stat driver
		//$driver = "\\NetDeviceLib\\Network\\".ucfirst($this->_configRead('statDriver') )."\\Client"; 
		//$this->_statDriver = new $driver( ['host'=>$this->_configRead('host')] );

	}

	public function connect() {
		if ($this->_commandDriver->connect()) {
			return $this->_commandDriver->auth_password();
		}

		return false;

	}



	public function exec( $cmd ) {
		
		return $this->_commandDriver->exec( $cmd );

	}
		public function command( $cmd ) {
		return $this->_parseOutput($this->_commandDriver->exec( $cmd ));

	}


	public function command_reboot() {
		$this->_commandDriver->exec( '/system reboot' );
		return true;

	}

	public function config_backup() {
		return $this->_commandDriver->exec( '/export' );
	}

	public function config_restore( $config ) {
		throw new Exception('RouterOs::config_restore not implemented');

	}

	protected function _parseOutput( $data ) {

		preg_match_all('/\s*([a-z-\s]+):([^\n]+)+\n/', $data, $match);

		$outputArr = [];
		foreach ($match[1] as $key=>$keyName) {
			$outputArr[$keyName] = $match[2][$key]; 
		}

		return $outputArr;
	}

}