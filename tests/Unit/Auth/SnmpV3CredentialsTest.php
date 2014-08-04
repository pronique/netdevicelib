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
use NetDeviceLib\Auth\SnmpV3Credentials;
use NetDeviceLib\TestSuite\TestCase;

/**
 * SnmpV3CredentialsTest class
 *
 */
class SnmpV3CredentialsTest extends TestCase {

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
 * testException method
 * Test Invalid Credentials class 
 *
 * @expectedException InvalidArgumentException
 */
    public function testException() {
    	$Creds = new SnmpV3Credentials( ['security'=>'wrong','username'=>'admin', 'password'=>'abc123'] );
    }

/**
 * testConstruct method
 *
 * @return void
 */
	public function testConstruct() {

		$Creds = new SnmpV3Credentials( ['username'=>'admin', 'password'=>'abc123'] );
		
		$this->assertEquals($Creds->getUsername(), 'admin');
		$this->assertEquals($Creds->getPassword(), 'abc123');
		$this->assertSame($Creds->get(), array(
			'security'		=> 'none',
			'username'		=> 'admin',
			'password'		=> 'abc123',
			'cryptPassword'	=> ''
		));		

		$Creds = new SnmpV3Credentials( [
			'security'=>'authorize',
			'username'=>'admin', 
			'password'=>'abc123',
			'cryptPassword'=>'secret'
		]);
		
		$this->assertEquals($Creds->getUsername(), 'admin');
		$this->assertEquals($Creds->getPassword(), 'abc123');
		$this->assertSame($Creds->get(), array(
			'security'		=> 'authorize',
			'username'		=> 'admin',
			'password'		=> 'abc123',
			'cryptPassword'	=> 'secret'
		));	


		$Creds = new SnmpV3Credentials( ['username'=>'admin', 'password'=>'abc123', 'cryptPassword'=>'secret'] );
		
		$this->assertEquals($Creds->getUsername(), 'admin');
		$this->assertEquals($Creds->getPassword(), 'abc123');
		$this->assertEquals($Creds->getCryptPassword(), 'secret');
		$this->assertSame($Creds->get(), array(
			'security'		=> 'none',
			'username'		=> 'admin',
			'password'		=> 'abc123',
			'cryptPassword'	=> 'secret'
		));		
	}

/**
 * testConstructProcedural method
 *
 * @return void
 */
	public function testConstructProcedural() {

		$Creds = new SnmpV3Credentials();
		
		$Creds->setUsername('admin');
		$this->assertEquals($Creds->getUsername(), 'admin');

		$Creds->setPassword('abc123');
		$this->assertEquals($Creds->getPassword(), 'abc123');

		$Creds->setCryptPassword('secret');
		$this->assertEquals($Creds->getCryptPassword(), 'secret');

		$this->assertSame($Creds->get(), array(
			'security'		=> 'none',
			'username'		=> 'admin',
			'password'		=> 'abc123',
			'cryptPassword' => 'secret'
		));		
	}

/**
 * testReset method
 *
 * @return void
 */
	public function testReset() {

		$Creds = new SnmpV3Credentials( ['username'=>'admin', 'password'=>'abc123'] );

		$Creds->reset();
		
		$this->assertSame($Creds->get(), array(
			'security'	=> 'none',
			'username'	=> '',
			'password'	=> '',
			'cryptPassword' => ''
		));		
	}

}
