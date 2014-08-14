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
namespace NetDeviceLib\Net\Ssh;

use NetDeviceLib\Net\ClientInterface;
use NetDeviceLib\Core\InstanceConfigTrait;
use NetDeviceLib\Error;
use NetDeviceLib\Auth\AuthFactory;

class Client implements ClientInterface {

	use InstanceConfigTrait;

/**
 * Default configuration for the client.
 *
 * @var array
 */
	protected $_defaultConfig = [
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
		'skipPingBeforeConnect'=>false,
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
	];

	protected $_connection;


/**
 * Credentials Object
 *
 * @var \NetDeviceLib\Auth\Credentials
 */
	protected $_credentials;


	protected $_authenticated = false;

	public function __construct($config = []) {

		$this->config($config);

		if ( $this->config('ssh.authType') == 'password' ) {
			$this->_credentials = AuthFactory::credentials( 'Credentials', $this->config( 'ssh.credentials' ) );
		} elseif ( $this->config('ssh.authType') == 'publicKey' ) {
			$this->_credentials = AuthFactory::credentials( 'SshPublicKeyCredentials', $this->config( 'ssh.credentials' ) );
		} else {
			throw new \Exception("Invalid authType '".$this->config('ssh.authType')."' specified");
		}
	}

/**
 * Destructor
 *
 * Tries to disconnect to ensure that the connection is being
 * terminated properly before the socket gets closed.
 */
	public function __destruct() {
		try {
			$this->disconnect();
		} catch (\Exception $e) { // avoid fatal error on script termination
		}
	}

	public function connect( ) {

		if (!$this->connected()) {
			//Config says skip ping
			if ( $this->config('skipPingBeforeConnect') === true ) {
				$this->_connect();
				$this->_auth();	
				return;			
			}

			//TCP Ping before Connect, throw socket exeception if we cannot connect
			if ( $this->ping() ) {
				$this->_connect();
				$this->_auth();
			} else {
				throw new \Exception('Cannot connect to socket: '.$this->config('ssh.host').':'.$this->config('ssh.port') );
			}
		}

	}

/**
 * Check whether an open socket connection to the Telnet server is available.
 *
 * @return bool
 */
	public function connected() {
		return $this->_connection !== null;
	}

/**
 * Check whether authenticated was successful.
 *
 * @return bool
 */
	public function authenticated() {
		return $this->_authenticated;
	}

/**
 * Disconnect from the SMTP server.
 *
 * This method tries to disconnect only in case there is an open
 * connection available.
 *
 * @return void
 */
	public function disconnect() {
		if ($this->connected()) {
			$this->_disconnect();
		}
	}


	public function execute( $cmd ) {

		$stream = ssh2_exec($this->_connection, $cmd);
		stream_set_timeout($stream, $this->config('ssh.timeout'));
    	stream_set_blocking($stream, true);
    	return stream_get_contents($stream); 
    	
	}

/**
 * Get Credentials Object
 *
 * @return \NetDeviceLib\Auth\Credentials
 */
	public function getCredentials() {
		return $this->_credentials;
	}

/**
 * TCP Port Ping
 *
 * @return false or 100ms
 */
	public function ping($host='', $port='', $timeout=4) { 
		if ( !$host ) { $host = $this->config('ssh.host'); }
		if ( !$port ) { $port = $this->config('ssh.port'); }

	  	$tB = microtime(true); 
		$fP = @fsockopen($host, $port, $errno, $errstr, $timeout);

		if (!$fP) { return false; } 
		$tA = microtime(true); 

		return round((($tA - $tB) * 1000), 0); 
	}


/**
 * Connect to SSH Server
 *
 * @return void
 * @throws \Exception
 */
	protected function _connect() {

		$this->_connection = @ssh2_connect( $this->config('ssh.host'), $this->config('ssh.port'), $this->config('ssh.methods') );
		if ( $this->_connection === false ) {
			throw new \Exception('Unable to connect to SSH server.');
		}

	}

/**
 * Disconnect
 *
 * @return void
 */
	protected function _disconnect() {
		//foreach ( $this->config('commands.onDisconnect') as $cmd ) {
			//$this->_socket->write( $cmd . $this->config('eol') );
		//}
		$this->_authenticated = false;
		unset( $this->_connection );
	}


/**
 * Send authentication
 *
 * @return bool
 */
	protected function _auth() {

		// check to see if already authenticated
		if ( $this->_authenticated === true ) { return true; }

		switch( $this->config('ssh.authType') ) {

			case 'password': // perform password auth
				if ( @ssh2_auth_password($this->_connection, $this->_credentials->getUsername(),  $this->_credentials->getPassword()) ) {
					$this->_authenticated = true;
					return true;
				}
				break;
			case 'publicKey': // perform public key auth
				$this->_writeTmpKeys();

				if ( @ssh2_auth_pubkey_file(
						$this->_connection, 
						$this->_credentials->getUsername(),  
						$this->_pubKeyTempFile,
						$this->_privKeyTempFile
					)) {

					$this->_authenticated = true;
					$this->_destroyTmpKeys();
					return true;
				}

				$this->_destroyTmpKeys();
				break;
		} //end switch()

		//return false;
		throw new \Exception('SSH '. $this->config('ssh.authType') . ' Authentication Failed');

	}

	protected function _writeTmpKeys() {
		$ts = time();
		try {
			$this->_privKeyTempFile = $this->config('tmpPath') 
				. DIRECTORY_SEPARATOR 
				. 'NDL-'
				. $ts
				.md5( $this->_credentials->getPrivateKey())
				.'.key';
			@file_put_contents($this->_privKeyTempFile, $this->_credentials->getPrivateKey());

			$this->_pubKeyTempFile = $this->config('tmpPath') 
				. DIRECTORY_SEPARATOR 
				. 'NDL-'
				. $ts
				.md5( $this->_credentials->getPublicKey() )
				.'.pub'
				;
			@file_put_contents($this->_pubKeyTempFile, $this->_credentials->getPublicKey());

		} catch ( Exception $e) {
			throw new \Exception('Cannot write to tmpPath: '. $this->tmpPath );
		}

	}

	protected function _destroyTmpKeys() {
		if ( strstr($this->_privKeyTempFile, $this->config('tmpPath'))) {
			@unlink($this->_privKeyTempFile);
		}
		if (strstr($this->_pubKeyTempFile, $this->config('tmpPath'))) {
			@unlink($this->_pubKeyTempFile);
		}
	}

}