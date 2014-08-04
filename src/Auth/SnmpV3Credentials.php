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

class SnmpV3Credentials implements CredentialsInterface {

	protected $_securityTypes = ['none', 'authorize', 'private'];

	protected $_security = 'none';

	protected $_username = '';

	protected $_password = '';

	protected $_cryptPassword = '';

	public function __construct( $config=[] ) {

		if ( array_key_exists('security', $config)) {
			$this->setSecurity( $config['security'] );
		}

		if ( array_key_exists('username', $config)) {
			$this->setUsername( $config['username'] );
		}

		if ( array_key_exists('password', $config)) {
			$this->setPassword( $config['password'] );
		}

		if ( array_key_exists('cryptPassword', $config)) {
			$this->setCryptPassword( $config['cryptPassword'] );
		}

	}

	public function get() {
		return [
			'security'=>$this->getSecurity(),
			'username'=>$this->getUsername(), 
			'password'=>$this->getPassword(), 
			'cryptPassword'=>$this->getCryptPassword()
		];
	}

	public function getSecurity() {
		return $this->_security;
	}

	public function setSecurity($security) {

		if ( in_array($security, $this->_securityTypes ) ) {
			$this->_security = $security;
		} else {
			throw new \InvalidArgumentException('Invalid Security Type' );
		}
	}

	public function getUsername() {
		return $this->_username;
	}

	public function setUsername($username) {
		$this->_username = $username;
	}

	public function getPassword() {
		return $this->_password;
	}

	public function setPassword($password) {
		$this->_password = $password;
	}

	public function getCryptPassword() {
		return $this->_cryptPassword;
	}

	public function setCryptPassword($cryptPassword) {
		$this->_cryptPassword = $cryptPassword;
	}

	public function reset() {

		$this->setSecurity('none');
		$this->setUsername('');
		$this->setPassword('');
		$this->setCryptPassword('');
		
	}

}