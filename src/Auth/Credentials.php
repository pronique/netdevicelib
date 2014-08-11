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

class Credentials implements CredentialsInterface {

	protected $_username;

	protected $_password;

	public function __construct( $config=[] ) {
		if ( array_key_exists('username', $config)) {

			if ( !$config['username'] ) {
				throw new \Exception('Username cannot be blank');
			}
			$this->setUsername( $config['username'] );
		}

		if ( array_key_exists('password', $config)) {
			$this->setPassword( $config['password'] );
		}

	}

	public function get() {
		return ['username'=>$this->getUsername(), 'password'=>$this->getPassword()];
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

	public function reset() {

		$this->setUsername('');
		$this->setPassword('');

	}

}