<?php
/**
 * NetDeviceLib
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
namespace NetDeviceLib\Device;


use NetDeviceLib\Config\Config;
use NetDeviceLib\Vendor\Mikrotik\RouterOS\Device;

class DeviceFactory {

	public function create($name) {

		//$ns = explode("\\", $name);
		$deviceClass = 'NetDeviceLib\\Vendor\\'.$name . "\\Device";

		$Device = new $deviceClass();
		return $Device;
	}

}