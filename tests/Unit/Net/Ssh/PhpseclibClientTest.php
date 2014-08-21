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
namespace NetDeviceLib\Test\Unit\Ssh;

use NetDeviceLib\Net\Error\SocketException;
use NetDeviceLib\Net\Socket;
use NetDeviceLib\Net\Ssh\PhpseclibClient;
use NetDeviceLib\TestSuite\TestCase;

/**
 * Ssh\ClientTest class
 *
 */
class PhpseclibClientTest extends TestCase {

	protected $testPrivateKey = '-----BEGIN DSA PRIVATE KEY-----
MIIBuwIBAAKBgQCmk1l6HynNRkqwDRRzJfS33TTM1/lhO9Vy30JK6OyuGh6qiscY
Up1lJ2QYYidlLJRh23r5jJjvaTpKEXB1SqaYoF5FPHt8QPW6hCfmXo/iigSlUesR
coHSKh33+HGfKCa9xUVLZds1XnTOZnijx4RUh0CiXpUZmG5pR9KUgewLywIVAIiO
keEs1Y0SCZ3GvLsNmdLg8JIPAoGBAJTozA0ecQWYEvps7rGlSLGHHrQOkVGZnMBa
BCmCRAan2auDWg/+afFn8MvgXhggjFPUQyy5XqGH53Lf6KWVEgMPYZUrMXybdtxV
/d5Wf0KERIzP/qUkM5ZXDH5TjsUZDikUH4jZa/g6uJ4EELqTnkPBYUUpcd5VW9Bh
6UauGbDFAoGAIUtFYXEIgrrEQ7T+NezG56DpP1XXxxY+jvDQdi848C0lAjiJAjdu
JMKdxrEsGIzFTLDs6K3jnGgHn0xqwofW/r9MYGbKItUYzlq4maY1lJDJK0ZYsWNF
gKA4FWSOXRehH11qa3pmiLLCYveHSOpf6ppShJVqeef3bB2pMXwhUcQCFHEXCHVi
WNIaeTEc9/iAuULylYb7
-----END DSA PRIVATE KEY-----';

	protected $testPublicKey = 'ssh-dss AAAAB3NzaC1kc3MAAACBAKaTWXofKc1GSrANFHMl9LfdNMzX+WE71XLfQkro7K4aHqqKxxhSnWUnZBhiJ2UslGHbevmMmO9pOkoRcHVKppigXkU8e3xA9bqEJ+Zej+KKBKVR6xFygdIqHff4cZ8oJr3FRUtl2zVedM5meKPHhFSHQKJelRmYbmlH0pSB7AvLAAAAFQCIjpHhLNWNEgmdxry7DZnS4PCSDwAAAIEAlOjMDR5xBZgS+mzusaVIsYcetA6RUZmcwFoEKYJEBqfZq4NaD/5p8Wfwy+BeGCCMU9RDLLleoYfnct/opZUSAw9hlSsxfJt23FX93lZ/QoREjM/+pSQzllcMflOOxRkOKRQfiNlr+Dq4ngQQupOeQ8FhRSlx3lVb0GHpRq4ZsMUAAACAIUtFYXEIgrrEQ7T+NezG56DpP1XXxxY+jvDQdi848C0lAjiJAjduJMKdxrEsGIzFTLDs6K3jnGgHn0xqwofW/r9MYGbKItUYzlq4maY1lJDJK0ZYsWNFgKA4FWSOXRehH11qa3pmiLLCYveHSOpf6ppShJVqeef3bB2pMXwhUcQ= user@host';


/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

	}


/**
 * testException method
 * Test Object Construction
 *
 */
    public function testConstruct() {
		
		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'127.0.0.1',
				'authType'=>'password',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			]
		]);


		$this->assertSame( $Client->config(), [
			'ssh'=> [
				'host'=>'127.0.0.1',
		        'port' => 22,
		        'timeout' => 15,
		        'pty'=>'vanilla',
		        'env'=>[],
		        'methods' => [
		            'kex' => 'diffie-hellman-group1-sha1'
		        ],
		        'authType' => 'password',
		        'credentials' => [
		            'username' => 'testuser',
		            'password' => 'testpass'
		        ]
			],
		    'eol' => "\n",
		    'readTimeout' => 2,
		    'skipPingBeforeConnect'=>false,
		    'prompt' => [
		        'command' => '$'
		    ],
		    'commands' => [
		        'onConnect' => [],
		        'onDisconnect' => [
		     		'quit'
		        ]
		    ],
			'tmpPath' => '/tmp'
		]);

    }

/**
 * testConstructInvalidAuthType method
 * Test Invalid authType specified
 *
 * @expectedException Exception
 */
    public function testConstructInvalidAuthTypeException() {
		
		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'127.0.0.1',
				'authType'=>'invalidEntry',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			]
		]);

    }

/**
 * testConstructInvalidAuthType method
 * Test Invalid authType specified
 *
 * @expectedException Exception
 */
    public function testConnectTimeoutException() {
		
		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'169.254.255.255',
				'authType'=>'password',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			]
		]);

		$Client->connect();

    }

/**
 * testConnectTimeoutWithBadPort method
 * Test that connecting to valid host but know bad ssh port
 *
 * @expectedException Exception
 */
    public function testConnectTimeoutWithBadPort() {
		
		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'cakephp.org',
				'port'=>'65019',
				'authType'=>'password',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			]
		]);

		$Client->connect();

    }


/**
 * testPingBeforeConnect method
 * Test TCP Ping Functionality
 *
 */
    public function testPingBeforeConnect() {
		
		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'cakephp.org',
				'authType'=>'password',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			]
		]);

		$this->assertRegExp('/^[0-9]+[.]?[0-9]+?/', (string)$Client->ping() );

		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'fooinvalidbadhostname.com',
				'authType'=>'password',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			]
		]);
		$this->assertFalse( $Client->ping() );
	
    }
/**
 * testConstruct method
 *
 * @return void
 */
	public function testConstructWithPasswordCredentials() {

		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'127.0.0.1',		
				'authType'=>'password',
				'credentials'=>[
					'username'=>'testuser',
					'password'=>'testpass'
				]
			],
			'prompt' => [
				'command'=>"$",
			],

		]);

		$this->assertInstanceOf('\NetDeviceLib\Auth\Credentials', $Client->getCredentials() );

	}


/**
 * testConstruct method
 *
 * @return void
 */
	public function testConstructWithSshPublicKeyCredentials() {

		$Client = new PhpseclibClient([
			'ssh'=> [
				'host'=>'127.0.0.1',
				'authType'=>'publicKey',
				'credentials'=>[
					'username'=>'testuser',
					'privateKey'=>$this->testPrivateKey,
					'publicKey'=>$this->testPublicKey,
				]
			]
		]);

		$this->assertInstanceOf('\NetDeviceLib\Auth\SshPublicKeyCredentials', $Client->getCredentials()  );

	}


}
