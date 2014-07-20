<?php 
/**
 * NetDeviceLib(tm)
 * Copyright (c) PRONIQUE Software (http://pronique.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) PRONIQUE Software (http://pronique.com)
 * @link          http://pronique.com NetDeviceLib Project
 * @since         0.5.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace NetDeviceLib\Network;

use NetDeviceLib\Core\InstanceConfigTrait;
use NetDeviceLib\Error;

class Client {

	use InstanceConfigTrait;

/**
 * Default configuration for the client.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'host' => null,
		'port' => null,
		'scheme' => 'ssh2',
		'timeout' => 30
	];

	public function __construct($config = []) {
		$this->config($config);
	}

}