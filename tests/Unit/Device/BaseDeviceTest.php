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
namespace NetDeviceLib\Test\Unit\Device;

use NetDeviceLib\Device\BaseDevice;
use NetDeviceLib\TestSuite\TestCase;

/**
 * BaseDeviceTest class
 *
 */
class BaseDeviceTest extends TestCase {

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
 * testConstructInvalidClientException method
 *
 * @expectedException Exception
 *
 */
    public function testConstructInvalidClientExecption() {
    	$Device = new BaseDevice([
    		'client'=> [
    			'smtp'=>	[
    				'host'=>'127.0.0.1',
    				'credentials'=>[
    					'username'=>'testuser',
    					'password'=>'testpass'
    				]
    			]
    		]
    	]);
    }

/**
 * testConstructInvalidCredentialsException method
 *
 * @expectedException Exception
 */
    public function testConstructInvalidCredentialsException() {
    	$Device = new BaseDevice([
    		'client'=> [
    			'smtp'=>	[
    				'host'=>'127.0.0.1',
    				'credentials'=>[
    					'username'=>'', //blank
    				]
    			]
    		]
    	]);
    }


/**
 * testConstruct method
 *
 */
    public function testConstruct() {
    	$Device = new BaseDevice([
    		'client'=> [
    			'ssh'=>	[
    				'host'=>'127.0.0.1',
    				'credentials'=>[
    					'username'=>'testuser',
    					'password'=>'testpass'
    				]
    			]
    		]
    	]);
    }

}
