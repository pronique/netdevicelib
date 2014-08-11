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
namespace NetDeviceLib\Test\Unit\Telnet;

use NetDeviceLib\Net\Error\SocketException;
use NetDeviceLib\Net\Socket;
use NetDeviceLib\Net\Telnet\Client;
use NetDeviceLib\TestSuite\TestCase;

/**
 * ClientTest class
 *
 */
class ClientTest extends TestCase {

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


		$Client = new Client([
			'socket'=>[
				'host'=>'127.0.0.1',
				'port'=>23,
				'timeout'=>10
			],
			'prompt' => [
				'command'=>"]\s>",
				'username'=>'ogin:',
				'password'=>'assword:',
				'noauth'=>'ogin failed'
			],
			'credentials'=>[
				'username'=>'testuser',
				'password'=>'testpass'
			]
		]);
		
	}

}