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
use NetDeviceLib\Auth\SshPublicKeyCredentials;
use NetDeviceLib\TestSuite\TestCase;

/**
 * SshPublicKeyCredentialsTest class
 *
 */
class SshPublicKeyCredentialsTest extends TestCase {


	protected $testPrivateKey = '-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA07A1ltRixoTznw7eGm675ULf8n6JYWI/llTRGXA5iW/JTvwc
BUsnwH9aQk17BVKktVHWeEL3e+SAJGrwnMwLm/E2O8ckzk6ITeMKHQVD8ZybusoY
xd+jbSAZ/l0bAGQ/yDFzoU8LpRyN95Kw6QJTIVeOLdYR/gdkQxO1U6sXaiSOspE+
yYQonKvLhpflsPEa8NcZ7XvD30C1KfcyJ6HYqp1ogUFX35YIzVqQ/CmcxoEWOxan
/bk/BwaNGtfNjbJ3Cgnta2v2UTUpzSv0Ur94xkalm002YvtmasBIrTf9o5wM+8fc
L9dw7l9lVuVC5kCNP2e8Pj98Xv3RI2rB87DzCwIDAQABAoIBACFQl/52ylzHy7d6
lYW/a563GZuGQoSq+6rjCk1glg2FJD9j+dzzaPwojsHkg7ngub83c/NVRrGe0nPo
yJlEm4cdPRXHT4mQXm8zjgHnNDwwE2ogKCnr+kJQTQb1DCzRmiAdeD8ou4JurgEK
ewEQtpiGuP+JsycxbpwH2/+g63xunyhmhPLTOLl6K9DFtJxbCmjrYJTiNzlkTwj6
Dn/b8EOTh9mVzbQq1z5e6h4I6iM6OOimJLX2gB7GqNqGczGUBHXNcrb1ONcUHybF
/W5v4AdDrIegBfiCm1/tT2rHv5LOhSjXoR1JTANDqWw59JFQt9/ZVJHCfVM9yjZX
3dWz48ECgYEA6cfOFN8MWJQyPA/aDMGV2Q8ukiKKCBpAkJQDNxgV1SJ/yM/qKjiZ
SGsOwvOyqjKIN/htHbMh4Seu05LmQ2+1umxHWJVmdV/l7PuD2q0DmhUHSj8RmHG0
p2XO6LnDuG/+OqOusCO4knwpZzeuTBN7Dms38VyAXnH4UllP2SuK//ECgYEA587f
Oio0A4sbwYdItZbBZmseyc8k3H4sj64AgNiVSy0UKBRIwBhBUhnELDqG7YLWQ84H
YMnkHCpUzhpNoYwPv5lk2IlggEoxcnV+fuest77Ok7hz3twqGyPm6rIyVoM9Hw5D
HriDtS4pKwzQL+EV++knsMsIwfKBBCwYUtOF3rsCgYAgZoPGWr+AS1HHBz9mQzI8
eiEvOcA9rT3Di/ACI8Fq37QsJbzDi0KbSdMq69GYSxacAz9EPX51kSVmx1ZIhGQA
aV9eBJ7Fp7vbI2S72vzDzyRKgwEySpgKF08c7BoXJtZqVCMy4FCFZNXsK0hp3M1S
S0PenL5h2JPc/enWJHXIUQKBgQDUvWTcyrKltdspR3ERRmQEDLda2sKnoRxgWH6Z
wStyrNJc3hDOSvRX1tHVDXmbLIJcBA99Yov41VizNiyc4B/r5WlJ2Po5gt3Sf8Yx
zYkTsQeBRr0AgOobsl1Qc24DO7qyb7Jl1Uz60Hxzx/SgnGBCqv4EILHO3TJOk/FW
wk2P1QKBgG9higse8Z2cRhi6hC73izzxOrADPsACln7q0XkNUlmDoTZIcPF6KB9K
SEFshyA+GgsUruBr8VPaDPnA3siG/ozSu59KMx8trspFaFd3cerTdegDFPEtTyZS
ieez3N7gP3R72/jgei7CbHhPpconuBx/VyLooWqiRz81wrWS3nvs
-----END RSA PRIVATE KEY-----';

	protected $testPublicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDTsDWW1GLGhPOfDt4abrvlQt/yfolhYj+WVNEZcDmJb8lO/BwFSyfAf1pCTXsFUqS1UdZ4Qvd75IAkavCczAub8TY7xyTOTohN4wodBUPxnJu6yhjF36NtIBn+XRsAZD/IMXOhTwulHI33krDpAlMhV44t1hH+B2RDE7VTqxdqJI6ykT7JhCicq8uGl+Ww8Rrw1xnte8PfQLUp9zInodiqnWiBQVfflgjNWpD8KZzGgRY7Fqf9uT8HBo0a182NsncKCe1ra/ZRNSnNK/RSv3jGRqWbTTZi+2ZqwEitN/2jnAz7x9wv13DuX2VW5ULmQI0/Z7w+P3xe/dEjasHzsPML your_email@example.com';



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

		$Creds = new SshPublicKeyCredentials( [
			'username'=>'admin', 
			'privateKey'=>$this->testPrivateKey,
			'publicKey'=>$this->testPublicKey
		]);
		
		$this->assertEquals($Creds->getUsername(), 'admin');

		$this->assertSame($Creds->get(), array(
			'username'	=> 'admin',
			'privateKey' => $this->testPrivateKey,
			'publicKey' => $this->testPublicKey
		));		
	}

/**
 * testConstructProcedural method
 *
 * @return void
 */
	public function testConstructProcedural() {

		$Creds = new SshPublicKeyCredentials();

		$this->assertEquals($Creds->getUsername(), NULL);

		$Creds->setUsername('admin');

		$this->assertEquals($Creds->getUsername(), 'admin');

		$Creds->setPrivateKey( $this->testPrivateKey );

		$this->assertEquals($Creds->getPrivateKey(), $this->testPrivateKey);

		$Creds->setPublicKey($this->testPublicKey);

		$this->assertEquals($Creds->getPublicKey(), $this->testPublicKey);

		$this->assertSame($Creds->get(), array(
			'username'	=> 'admin',
			'privateKey'=>$this->testPrivateKey,
			'publicKey'=>$this->testPublicKey
		));		
	}

/**
 * testReset method
 *
 * @return void
 */
	public function testReset() {

		$Creds = new SshPublicKeyCredentials([
			'username'=>'admin',
			'privateKey'=>$this->testPrivateKey,
			'publicKey'=>$this->testPublicKey
		]);

		$Creds->reset();
		
		$this->assertSame($Creds->get(), array(
			'username'		=> '',
			'privateKey'	=> '',
			'publicKey'		=> ''
		));		
	}

}
