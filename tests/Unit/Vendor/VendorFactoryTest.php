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
namespace NetDeviceLib\Test\Unit\Vendor;

use NetDeviceLib\Vendor\VendorFactory;
use NetDeviceLib\TestSuite\TestCase;

/**
 * Factory to create instance of vendor classes
 *
 */
class VendorFactoryTest extends TestCase {

    public $Device;

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
 * testConfigRead method
 *
 */
    public function testGetInstanceMikrotikRouterOS() {

        $Device = VendorFactory::device( 'Mikrotik\\RouterOS', [
        	'client'=>[
	        	'ssh'=>[
	        		'credentials'=>[
	        			'username'=>'testuser'
	        		]
	        	]
        	]
        ] );

        $this->assertInstanceOf('\NetDeviceLib\Vendor\Mikrotik\RouterOS\Device\Device', $Device);	

        //Alternative VendorString
         $Device = VendorFactory::device( 'Mikrotik\RouterOS', [
        	'client'=>[
	        	'ssh'=>[
	        		'credentials'=>[
	        			'username'=>'testuser'
	        		]
	        	]
        	]
        ] );

        $this->assertInstanceOf('\NetDeviceLib\Vendor\Mikrotik\RouterOS\Device\Device', $Device);	

         //Alternative VendorString 2
         $Device = VendorFactory::device( 'Mikrotik/RouterOS', [
        	'client'=>[
	        	'ssh'=>[
	        		'credentials'=>[
	        			'username'=>'testuser'
	        		]
	        	]
        	]
        ] );

        $this->assertInstanceOf('\NetDeviceLib\Vendor\Mikrotik\RouterOS\Device\Device', $Device);

    }

}
