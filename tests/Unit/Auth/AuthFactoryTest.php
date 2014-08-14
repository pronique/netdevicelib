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

use NetDeviceLib\Auth\AuthFactory;
use NetDeviceLib\TestSuite\TestCase;

/**
 * AuthFactoryTest class
 *
 */
class AuthFactoryTest extends TestCase {

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
    	AuthFactory::credentials( 'FooClass', ['username'=>'admin', 'password'=>'abc123'] );
    }

/**
 * testReturnObjectIsInstanceOf method
 *
 * @return void
 */
	public function testReturnObjectIsInstanceOf() {

		$this->assertInstanceOf('\NetDeviceLib\Auth\Credentials', AuthFactory::credentials());	

		$this->assertInstanceOf('\NetDeviceLib\Auth\Credentials', AuthFactory::credentials( 'Credentials' ));	

		$this->assertInstanceOf('\NetDeviceLib\Auth\SshPublicKeyCredentials', AuthFactory::credentials( 'SshPublicKeyCredentials' ));	

		$this->assertInstanceOf('\NetDeviceLib\Auth\SnmpCredentials', AuthFactory::credentials( 'SnmpCredentials' ));	

		$this->assertInstanceOf('\NetDeviceLib\Auth\SnmpV3Credentials', AuthFactory::credentials( 'SnmpV3Credentials' ));	

	}

}
