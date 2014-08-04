<?php
/**
 * NetDeviceLib Tests <https://github.com/pronique/netdevicelib/wiki/Testing>
 * Copyright (c) PRONIQUE Software (http://pronique.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) PRONIQUE Software (http://pronique.com)
 * @link          https://github.com/pronique/netdevicelib/wiki/Testing NetDeviceLib Tests
 * @since         0.5.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace NetDeviceLib\Test\Unit\Auth;

use NetDeviceLib\Net\Error\SocketException;
use NetDeviceLib\Auth\SnmpCredentials;
use NetDeviceLib\TestSuite\TestCase;

/**
 * SnmpCredentialsTest class
 *
 */
class SnmpCredentialsTest extends TestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

	}

/**
 * testConstruct method
 *
 * @return void
 */
	public function testConstruct() {

		$Creds = new SnmpCredentials( ['readCommunity'=>'public', 'writeCommunity'=>'private'] );
		
		$this->assertEquals($Creds->getReadCommunity(), 'public');

		$this->assertEquals($Creds->getWriteCommunity(), 'private');

		$this->assertSame($Creds->get(), array(
			'readCommunity'		=> 'public',
			'writeCommunity'	=> 'private'
		));		
	}

/**
 * testConstructProcedural method
 *
 * @return void
 */
	public function testConstructProcedural() {

		$Creds = new SnmpCredentials();
		
		$Creds->setReadCommunity('public');

		$this->assertEquals($Creds->getReadCommunity(), 'public');

		$Creds->setWriteCommunity('private');

		$this->assertEquals($Creds->getWriteCommunity(), 'private');

		$this->assertSame($Creds->get(), array(
			'readCommunity'		=> 'public',
			'writeCommunity'	=> 'private'
		));		
	}

/**
 * testReset method
 *
 * @return void
 */
	public function testReset() {

		$Creds = new SnmpCredentials( ['readCommunity'=>'public', 'writeCommunity'=>'private'] );

		$Creds->reset();
		
		$this->assertSame($Creds->get(), array(
			'readCommunity'		=> '',
			'writeCommunity'	=> ''
		));	
	}

}
