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
use NetDeviceLib\Net\Ssh\Client;
use NetDeviceLib\TestSuite\TestCase;

/**
 * Ssh\ClientTest class
 *
 */
class ClientTest extends TestCase {

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
 * testConstruct method
 *
 * @return void
 */
	public function testConstructWithPasswordCredentials() {



		$Client = new Client([
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

		//var_dump( $Client );
		$this->assertInstanceOf('\NetDeviceLib\Auth\Credentials', $Client->getCredentials() );

	}


/**
 * testConstruct method
 *
 * @return void
 */
	public function testConstructWithSshPublicKeyCredentials() {


		$Client = new Client([
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

		//var_dump( $Client );
		$this->assertInstanceOf('\NetDeviceLib\Auth\SshPublicKeyCredentials', $Client->getCredentials()  );

	}


}
