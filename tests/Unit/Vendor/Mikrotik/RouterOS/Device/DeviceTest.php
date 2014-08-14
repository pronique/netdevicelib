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
namespace NetDeviceLib\Test\Unit\Vendor\Mikrotik\RouterOS\Device;

use NetDeviceLib\Vendor\Mikrotik\RouterOS\Device\Device;
use NetDeviceLib\TestSuite\TestCase;

/**
 * Vendor\Mikrotik\RouterOS\Device\DeviceTest class
 *
 */
class DeviceTest extends TestCase {

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
 */
    public function testConstruct() {
    	$Device = new Device([
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

        $this->assertInstanceOf('\NetDeviceLib\Vendor\Mikrotik\RouterOS\Device\Device', $Device );
        $this->assertInstanceOf('\NetDeviceLib\Net\Ssh\Client', $Device->Client );
        $this->assertInstanceOf('\NetDeviceLib\Vendor\Mikrotik\RouterOS\Config\Config', $Device->Config );

    }

}
