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
namespace NetDeviceLib\Net\Error;

use NetDeviceLib\Error\Exception;

/**
 * Exception class for Socket. This exception will be thrown from Socket, Email, HttpSocket
 * SmtpTransport, MailTransport and HttpResponse when it encounters an error.
 *
 */
class SocketException extends Exception {
}
