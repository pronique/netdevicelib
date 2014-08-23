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
namespace NetDeviceLib\Vendor;

class VendorFactory {

	protected static $_classNamespace = "\\NetDeviceLib\\Vendor\\";

/**
 * Create and return instance of Vendor Specific Device
 *
 */
	public static function device( $vendorString='', $config=[] ) {

 		// Forward Slash is allowed in vendorString, convert to backslash
 		$vendorString = str_replace("/", "\\", $vendorString);

		$fullClass = self::$_classNamespace.$vendorString."\\Device\\Device";

		if (class_exists($fullClass)) {
			$Device = new $fullClass( $config );

			return $Device;
		} else {
			throw new \InvalidArgumentException('Invalid Vendor Class Specified: ' . $fullClass  );
		}
		
	}

}