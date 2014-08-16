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
use DominionEnterprises\ColumnParser\MultispacedHeadersParser;

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
	public function getTable( $command ) {
		$response = $this->Client->execute( $command . ' print');
		$responseArray = $this->_tableToArray( $response );
		return $responseArray;
	}

/**
 * Execute <command> print on device and return array
 * for commands that return list format such as /queue simple print or /ip firewall filter print
 *
 * @return String;
 */
	public function getList( $command ) {
		throw new \Exception('::getList() Currently in development');
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

		$flags = $this->_parseTableFlags( $string );

		//Get $string minus the flag map
		$table_offset_index = strpos($string, "\n # ");
		$string_wo_flags = trim(substr($string, $table_offset_index, strlen($string) ));

		//Remove Comments and keep for later
		list( $string_wo_comments, $comments ) = $this->_tableRemoveComments( $string_wo_flags );

		//Call column parser library
		$parser = new MultispacedHeadersParser( $string_wo_comments );
		$data = $parser->getRows();

		//Add Comments back to $data
		$data = $this->_tableAddComments( $data, $comments );

		$data = $this->_tableFlagsFixup( $data );

		$columns = $this->_tableGetColumns( $data );
		$responseArray = [
			'flags'		=>	$flags,
			'columns'	=>	$columns,
			'data'		=>	$data
		]; 

		return $responseArray;
	}


	protected function _tableGetColumns( $data ) {
		if ( !array_key_exists(0, $data)  ) { return []; }
		$keys = array_keys( $data[0] );
		foreach ( $keys as $i=>$key ) {
			if ( substr($key, 0, 1) == '_' ) {
				unset( $keys[$i] );
			} 
		}
		return $keys;
	}
/**
 * Remove comments and return string without comments along with an array of comments
 *
 */

	protected function _tableRemoveComments( $string ) {

		preg_match_all('/(([0-9]+).*)[;]{3}\s(.*)\n\s+/', $string, $matches);

		$comments = [];
		$string_wo_comments = $string;


		foreach( $matches[2] as $key=>$index ) {
			$string_wo_comments = str_replace( $matches[0][$key], $matches[1][$key], $string_wo_comments);
			$comments[$index] = $matches[3][$key];
		}

		return array( $string_wo_comments, $comments );
	}


/**
 * Add comments back to array
 *
 */

	protected function _tableAddComments( $dataArr, $comments ) {

		foreach( $comments as $key=>$comment ) {
			$dataArr[$key]['_comment'] = $comment;
		}

		return $dataArr;
	}

/**
 * Deal with flags that end up in Index column
 *
 */

	protected function _tableFlagsFixup( $dataArr ) {

		foreach ($dataArr as $key=>$item ) {
			preg_match_all('/([0-9]+)\s?(.+)?/', $item['#'], $matches);

			
			if ( $flags = str_split(trim($matches[2][0])) ) {
				$dataArr[$key]['_flags'] = $matches[2][0];
			}

			$dataArr[$key]['_index'] = $matches[1][0];
			unset( $dataArr[$key]['#'] );			


		}


		return $dataArr;
	}

/**
 * Utility Function Parse Flags
 */
	protected function _parseTableFlags( $string ) {

		//convert newlines to spaces incase flags span multiple lines
		$string = str_replace("\n", "\s", $string);

		//Parse Flags
		$flags = [];
		if (!preg_match( '/Flags:\s(.*)/', $string, $matches ) ) {
			return false;
		}

		$flagsExtract = $matches[1];
		preg_match_all( '/([A-Z])\s-\s([a-z]+)/', $flagsExtract, $matches );
		foreach( $matches[1] as $key=>$val ) {
			$flags[$matches[1][$key]] = $matches[2][$key];
		}
		return $flags;
	}

}