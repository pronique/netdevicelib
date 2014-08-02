<?php
/*
 *
 * A quick and dirty way to initially test library until formally implementing PHPUnit tests
 */

require_once('bootstrap.php');

use NetDeviceLib\Network\Socket;

$sock = new NetDeviceLib\Network\Socket();

var_dump( $sock );