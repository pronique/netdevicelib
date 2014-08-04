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
namespace NetDeviceLib\Net;

use NetDeviceLib\Net\ClientInterface;
use NetDeviceLib\Core\InstanceConfigTrait;
use NetDeviceLib\Error;
use NetDeviceLib\Utility\Hash;
use Bestnetwork\Telnet\TelnetClient;

class Client implements ClientInterface {

	use InstanceConfigTrait;

/**
 * Default configuration for the client.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'host' => null,
		'port' => 22,
		'timeout' => 12,
		'user'=>null,
		'pass'=>null
	];

	protected $_connection;

	protected $_credentials;

	protected $authenticated = false;

	public function __construct($config = []) {
		$this->config($config);

		$this->_telnetLib = new TelnetClient();

	}

	public function connect( $host='', $port='' ) {
		//Check to see if already connected
		if ( $this->_connection ) { return true; }

		if ( $host ) { $this->config('host', $host); }
		if ( $port ) { $this->config('port', $port); }

		$this->_connection = @ssh2_connect( $this->config('host'), $this->config('port') );

		if ( $this->_connection ) {
			return true;
		}
		return false;
	}

	public function disconnect() {
		unset( $this->_connection );
		$this->authenticated = false;
		return true;
	}

	public function auth_password( $user='', $pass='') {
		// check to see if already authenticated
		if ( $this->authenticated === true ) { return true; }

		if ( $user ) { $this->config('user', $user); }
		if ( $pass ) { $this->config('pass', $pass); }

		if ( ssh2_auth_password($this->_connection,  $this->config('user'),  $this->config('pass')) ) {
			$this->authenticated = true;
			return true;
		}
		return false;
	}

	public function exec( $cmd ) {

		$stream = ssh2_exec($this->_connection, $cmd);
		stream_set_timeout($stream, $this->config('timeout'));
    stream_set_blocking($stream, true);
    return stream_get_contents($stream); 

	}



}