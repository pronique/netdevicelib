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
namespace NetDeviceLib\Auth;

class SshPublicKeyCredentials implements CredentialsInterface {

	protected $_username;

	protected $_publicKey;

	protected $_privateKey;

	public function __construct( $config=[] ) {

		if ( array_key_exists('username', $config)) {
			$this->setUsername( $config['username'] );
		}

		if ( array_key_exists('privateKey', $config)) {
			$this->setPrivateKey( $config['privateKey'] );
		}

		if ( array_key_exists('publicKey', $config)) {
			$this->setPublicKey( $config['publicKey'] );
		}

	}

	public function get() {
		return [
			'username'=>$this->getUsername(), 
			'privateKey'=>$this->getPrivateKey(),
			'publicKey'=>$this->getPublicKey()
		];
	}

	public function getUsername() {
		return $this->_username;
	}

	public function setUsername($username) {
		$this->_username = $username;
	}

	public function getPrivateKey() {
		return $this->_privateKey;
	}

	public function setPrivateKey($privateKey) {
		$this->_privateKey = $privateKey;
	}

	public function getPublicKey() {
		return $this->_publicKey;
	}

	public function setPublicKey($publicKey) {
		$this->_publicKey = $publicKey;
	}

	public function reset() {

		$this->setUsername('');
		$this->setPrivateKey('');
		$this->setPublicKey('');

	}

}