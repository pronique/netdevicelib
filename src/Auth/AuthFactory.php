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
namespace NetDeviceLib\Auth;

class AuthFactory {

	protected static $_classNamespace = "\\NetDeviceLib\\Auth\\";

	public static function credentials( $class='Credentials', $config=[] ) {
 
		$fullClass = self::$_classNamespace.$class;

		if (class_exists($fullClass)) {
			$Credentials = new $fullClass( $config );
			return $Credentials;
		} else {
			throw new \InvalidArgumentException('Invalid Credentials Class: ' . $fullClass  );
		}
		
	}

}