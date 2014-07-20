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

namespace NetDeviceLib\Error;

/**
 * Base class that all NetDeviceLib Exceptions extend.
 *
 */
class Exception extends \RuntimeException {

/**
 * Array of attributes that are passed in from the constructor, and
 * made available in the view when a development error is displayed.
 *
 * @var array
 */
	protected $_attributes = array();

/**
 * Template string that has attributes sprintf()'ed into it.
 *
 * @var string
 */
	protected $_messageTemplate = '';

/**
 * Array of headers to be passed to Cake\Network\Response::header()
 *
 * @var array
 */
	protected $_responseHeaders = null;

/**
 * Constructor.
 *
 * Allows you to create exceptions that are treated as framework errors and disabled
 * when debug = 0.
 *
 * @param string|array $message Either the string of the error message, or an array of attributes
 *   that are made available in the view, and sprintf()'d into Exception::$_messageTemplate
 * @param int $code The code of the error, is also the HTTP status code for the error.
 */
	public function __construct($message, $code = 500) {
		if (is_array($message)) {
			$this->_attributes = $message;
			$message = vsprintf($this->_messageTemplate, $message);
		}
		parent::__construct($message, $code);
	}

/**
 * Get the passed in attributes
 *
 * @return array
 */
	public function getAttributes() {
		return $this->_attributes;
	}

/**
 * Get/set the response header to be used
 *
 * See also Cake\Network\Response::header()
 *
 * @param string|array $header An array of header strings or a single header string
 *	- an associative array of "header name" => "header value"
 *	- an array of string headers is also accepted
 * @param string $value The header value.
 * @return array
 */
	public function responseHeader($header = null, $value = null) {
		if ($header) {
			if (is_array($header)) {
				return $this->_responseHeaders = $header;
			}
			$this->_responseHeaders = array($header => $value);
		}
		return $this->_responseHeaders;
	}

}
