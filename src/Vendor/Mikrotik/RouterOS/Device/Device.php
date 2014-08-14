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
namespace NetDeviceLib\Vendor\Mikrotik\RouterOS\Device;

use NetDeviceLib\Device\BaseDevice;
use NetDeviceLib\Vendor\Mikrotik\RouterOS\Config\Config;

/**
 * Vendor Specific Implementation to interact with Mikrotik RouterOS Devices
 *
 * 
 */
class Device extends BaseDevice {

/**
 * Instance of Net\<Protocol>\Client class
 *
 * @var NetDeviceLib\Net\<Protocol>\Client
 */
	public $Client;

/**
 * Instance of RouterOS Config class
 *
 * @var \NetDeviceLib\Vendor\Mikrotik\RouterOS\Config\Config
 */
	public $Config;

	public function __construct( $config = [] ) {

		parent::__construct( $config );

		$this->Config = new Config( $this );

	}

/**
 * Disconnect from Device
 */
	public function disconnect() {
		//$this->Client->disconnect();
	}

/**
 * Execute <command> print on device and return formatted response
 *
 * @return String;
 */
	public function get( $command ) {
		$response = $this->Client->execute( $command . ' print');
		$responseArray = $this->_resultsToArray( $response );
		return $responseArray;
	}

/**
 * Execute <command> print on device and return formatted table response
 *
 * @return String;
 */
	public function getList( $command ) {
		$response = $this->Client->execute( $command . ' print');
		$responseArray = $this->_tableToArray( $response );
		return $responseArray;
	}


/**
 * Returns Device's Name
 *
 * @return String;
 */
	public function getName() {
		$response = $this->Client->execute('/system identity print');
		//list($key, $name) = array_map('trim', split(': ', $response));
		$responseArray = $this->_resultsToArray( $response );
		return $responseArray['name'];
	}


/**
 * Returns Device's Software Version Information
 *
 * @return String;
 */
	public function getVersion() {
		$responseArray = $this->getInfo();
		return $responseArray['version'];
	}

/**
 * Returns Device's Uptime
 *
 * @return String;
 */
	public function getUptime() {
		$responseArray = $this->getInfo();
		return $responseArray['uptime'];
	}


/**
 * Returns Device's System Resource Information
 *
 * @return Array;
 */
	public function getInfo() {
		$response = $this->Client->execute('/system resource print');
		return $this->_resultsToArray( $response );
	}

/**
 * Reboot the device
 *
 * @return null;
 */
	public function reboot() {
		$this->Client->execute('/system reboot');
	}

/**
 * Utility Function to convert RouterOS print output to associative array
 *
 * @return Array;
 */
	protected function _resultsToArray( $string ) {

		$lines = split("\n", $string );
		$responseArray = [];
		foreach( $lines as $line ) {
			$lineArr = array_map('trim', split(': ', $line));
			if ( $lineArr[0] ) {	
				$responseArray[$lineArr[0]] = $lineArr[1];
			}
		}
		return $responseArray;
	}

/**
 * Utility Function to convert RouterOS table formatted output to associative array
 *
 * @return Array;
 */
	protected function _tableToArray( $string ) {
		throw new \Exception('TODO Work in Progress');
		echo $string;
		$flags = $this->_parseTableFlags( $string );

		$lines = split("\n", $string );

		//Find Header Line
		if ( $flags ) {
			$headerLine = $lines[1];
		} else {
			$headerLine = $lines[0];
		}

		//Get Column Names
		$columns = $this->_parseTableHeader( $headerLine );
		
		//Parse Rows - Find index, flags, optional command, and columns
		preg_match_all( "/\s?([0-9]+)\s?(X|I|D)?\s?(;;;([^\n]+))?\n/", $string, $matches );

		print_r( $matches );
		exit;

		$responseArray = [
			'flags'=> $flags,
			'data'=>[]
		]; 

		foreach ( $columns as $col ) {
			$responseArray['data'][$col] = []; 
		} 

		return $responseArray;
	}

/**
 * Utility Function Parse Flag
 */
	protected function _parseTableFlags( $string ) {
		//Parse Flags
		$flags = [];
		if (!preg_match( '/Flags:\s(.*)\n/', $string, $matches ) ) {
			return false;
		}
		$flagsExtract = $matches[1];
		preg_match_all( '/([A-Z])\s-\s([a-z]+)/', $flagsExtract, $matches );
		foreach( $matches[1] as $key=>$val ) {
			$flags[$matches[1][$key]] = $matches[2][$key];
		}
		return $flags;
	}

/**
 * Utility Function Parse Flag
 */
	protected function _parseTableHeader( $headerLine ) {
		//Parse Header Row
		preg_match_all('/([A-Z\#]+)[\s]+/', $headerLine, $matches );
		return array_map('trim', $matches[0]);
	}
}