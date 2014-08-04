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

class SnmpCredentials {

	protected $_readCommunity;

	protected $_writeCommunity;

	public function __construct( $config=[] ) {
		if ( array_key_exists('readCommunity', $config)) {
			$this->setReadCommunity( $config['readCommunity'] );
		}

		if ( array_key_exists('writeCommunity', $config)) {
			$this->setWriteCommunity( $config['writeCommunity'] );
		}

	}

	public function get() {
		return [
			'readCommunity'=>$this->getReadCommunity(), 
			'writeCommunity'=>$this->getWriteCommunity()
		];
	}

	public function getReadCommunity() {
		return $this->_readCommunity;
	}

	public function setReadCommunity($readCommunity) {
		$this->_readCommunity = $readCommunity;
	}

	public function getWriteCommunity() {
		return $this->_writeCommunity;
	}

	public function setWriteCommunity($writeCommunity) {
		$this->_writeCommunity = $writeCommunity;
	}

	public function reset() {

		$this->setReadCommunity('');
		$this->setWriteCommunity('');

	}

}