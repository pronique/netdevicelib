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
use NetDeviceLib\Auth\Credentials;
use NetDeviceLib\TestSuite\TestCase;

/**
 * CredentialsTest class
 *
 */
class CredentialsTest extends TestCase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		//$this->Socket = new Socket(array('timeout' => 1));
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		//unset($this->Socket);
	}

/**
 * testConstruct method
 *
 * @return void
 */
	public function testConstruct() {

		$Creds = new Credentials( ['username'=>'admin', 'password'=>'abc123'] );
		
		$this->assertEquals($Creds->getUsername(), 'admin');

		$this->assertEquals($Creds->getPassword(), 'abc123');

		$this->assertSame($Creds->get(), array(
			'username'	=> 'admin',
			'password'	=> 'abc123'
		));		
	}

/**
 * testConstructProcedural method
 *
 * @return void
 */
	public function testConstructProcedural() {

		$Creds = new Credentials();
		
		$Creds->setUsername('admin');

		$this->assertEquals($Creds->getUsername(), 'admin');

		$Creds->setPassword('abc123');

		$this->assertEquals($Creds->getPassword(), 'abc123');

		$this->assertSame($Creds->get(), array(
			'username'	=> 'admin',
			'password'	=> 'abc123'
		));		
	}

/**
 * testReset method
 *
 * @return void
 */
	public function testReset() {

		$Creds = new Credentials( ['username'=>'admin', 'password'=>'abc123'] );

		$Creds->reset();
		
		$this->assertSame($Creds->get(), array(
			'username'	=> '',
			'password'	=> ''
		));		
	}

}
