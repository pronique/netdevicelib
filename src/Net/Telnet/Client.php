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
namespace NetDeviceLib\Net\Telnet;

use NetDeviceLib\Net\ClientInterface;
use NetDeviceLib\Net\Socket;
use NetDeviceLib\Net\Error\SocketException;
use NetDeviceLib\Core\InstanceConfigTrait;
use NetDeviceLib\Error;
use NetDeviceLib\Utility\Hash;
use NetDeviceLib\Auth\AuthFactory;




/**
 * Definitions for the TELNET protocol.
 */
define('TEL_IAC',   chr(255));  /* interpret as command: */
define('TEL_DONT',  chr(254));  /* you are not to use option */
define('TEL_DO',    chr(253));  /* please, you use option */
define('TEL_WONT',  chr(252));  /* I won't use option */
define('TEL_WILL',  chr(251));  /* I will use option */
define('TEL_SB',    chr(250));  /* interpret as subnegotiation */
define('TEL_GA',    chr(249));  /* you may reverse the line */
define('TEL_EL',    chr(248));  /* erase the current line */
define('TEL_EC',    chr(247));  /* erase the current character */
define('TEL_AYT',   chr(246));  /* are you there */
define('TEL_AO',    chr(245));  /* abort output--but let prog finish */
define('TEL_IP',    chr(244));  /* interrupt process--permanently */
define('TEL_BREAK', chr(243));  /* break */
define('TEL_DM',    chr(242));  /* data mark--for connect. cleaning */
define('TEL_NOP',   chr(241));  /* nop */
define('TEL_SE',    chr(240));  /* end sub negotiation */
define('TEL_EOR',   chr(239));  /* end of record (transparent mode) */
define('TEL_ABORT', chr(238));  /* Abort process */
define('TEL_SUSP',  chr(237));  /* Suspend process */
define('TEL_EOF',   chr(236));  /* End of file: EOF is already used... */

define('TEL_SYNCH', chr(242));  /* for telfunc calls */
define('TEL_xEOF',  TEL_EOF);   /* Name compatible with bsd telnet.h */


/**
 *  TELNET options
 */
define('TELOPT_BINARY',         chr(0));    /* 8-bit data path */
define('TELOPT_ECHO',           chr(1));    /* echo */
define('TELOPT_RCP',            chr(2));    /* prepare to reconnect */
define('TELOPT_SGA',            chr(3));    /* suppress go ahead */
define('TELOPT_NAMS',           chr(4));    /* approximate message size */
define('TELOPT_STATUS',         chr(5));    /* give status */
define('TELOPT_TM',             chr(6));    /* timing mark */
define('TELOPT_RCTE',           chr(7));    /* remote controlled transmission and echo */
define('TELOPT_NAOL',           chr(8));    /* negotiate about output line width */
define('TELOPT_NAOP',           chr(9));    /* negotiate about output page size */
define('TELOPT_NAOCRD',         chr(10));   /* negotiate about CR disposition */
define('TELOPT_NAOHTS',         chr(11));   /* negotiate about horizontal tabstops */
define('TELOPT_NAOHTD',         chr(12));   /* negotiate about horizontal tab disposition */
define('TELOPT_NAOFFD',         chr(13));   /* negotiate about formfeed disposition */
define('TELOPT_NAOVTS',         chr(14));   /* negotiate about vertical tab stops */
define('TELOPT_NAOVTD',         chr(15));   /* negotiate about vertical tab disposition */
define('TELOPT_NAOLFD',         chr(16));   /* negotiate about output LF disposition */
define('TELOPT_XASCII',         chr(17));   /* extended ascii character set */
define('TELOPT_LOGOUT',         chr(18));   /* force logout */
define('TELOPT_BM',             chr(19));   /* byte macro */
define('TELOPT_DET',            chr(20));   /* data entry terminal */
define('TELOPT_SUPDUP',         chr(21));   /* supdup protocol */
define('TELOPT_SUPDUPOUTPUT',   chr(22));   /* supdup output */
define('TELOPT_SNDLOC',         chr(23));   /* send location */
define('TELOPT_TTYPE',          chr(24));   /* terminal type */
define('TELOPT_EOR',            chr(25));   /* end or record */
define('TELOPT_TUID',           chr(26));   /* TACACS user identification */
define('TELOPT_OUTMRK',         chr(27));   /* output marking */
define('TELOPT_TTYLOC',         chr(28));   /* terminal location number */
define('TELOPT_3270REGIME',     chr(29));   /* 3270 regime */
define('TELOPT_X3PAD',          chr(30));   /* X.3 PAD */
define('TELOPT_NAWS',           chr(31));   /* window size */
define('TELOPT_TSPEED',         chr(32));   /* terminal speed */
define('TELOPT_LFLOW',          chr(33));   /* remote flow control */
define('TELOPT_LINEMODE',       chr(34));   /* Linemode option */
define('TELOPT_XDISPLOC',       chr(35));   /* X Display Location */
define('TELOPT_OLD_ENVIRON',    chr(36));   /* Old - Environment variables */
define('TELOPT_AUTHENTICATION', chr(37));   /* Authenticate */
define('TELOPT_ENCRYPT',        chr(38));   /* Encryption option */
define('TELOPT_NEW_ENVIRON',    chr(39));   /* New - Environment variables */
define('TELOPT_EXOPL',          chr(255));  /* extended-options-list */

class Client implements ClientInterface {

	use InstanceConfigTrait;

/**
 * Default configuration for the client.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'socket'=>[
			'persistent' => true,
			'protocol' => 'tcp',
			'host' => null,
			'port' => 23,
			'timeout' => 15
		],
		'eol'=>"\n",
		'readTimeout'=>2,
		'optionCodes'=>[
			'terminalType'=>'vt100',
			'terminalSpeed'=>'38400,38400'
		],
		'prompt'=>[
			'command'  => '>',
			'username' => 'ogin:',
			'password' => 'sword:',
			'noauth'=>'ogin failed'
		],
		'commands'=>[
			'onConnect'=>[],
			'onDisconnect'=>[
				'quit'
			]
		],
		'credentials'=>[
			'username'=>'',
			'password'=>''
		]
	];


/**
 * Next Match Pattern
 * Set by ->expect()
 * Referenced by ->readTo()
 *
 * @var String
 */
	protected $_nextMatchPattern;

/**
 * Next Match Pattern Type 'simple' or 'regex'
 * Set by ->expect()
 * Referenced by ->readTo()
 *
 * @var String
 */
	protected $_nextMatchPatternType;


/**
 * Socket to SMTP server
 *
 * @var \NetDeviceLib\Net\Socket
 */
	protected $_socket;

/**
 * Read Buffer
 *
 * @var String
 */
	protected $_buffer;

/**
 * Credentials Object
 *
 * @var \NetDeviceLib\Auth\Credentials
 */
	protected $_credentials;


	protected $authenticated = false;

	public function __construct($config = []) {

		$this->config($config);

		$this->_credentials = AuthFactory::credentials( 'Credentials', $this->config( 'credentials' ) );
		
		//$this->_socket = new Socket($this->config('socket'));
	}

/**
 * Destructor
 *
 * Tries to disconnect to ensure that the connection is being
 * terminated properly before the socket gets closed.
 */
	public function __destruct() {
		try {
			$this->disconnect();
		} catch (\Exception $e) { // avoid fatal error on script termination
		}
	}

/**
 * Connect to the Telnet server.
 *
 * This method tries to connect only in case there is no open
 * connection available already.
 *
 * @return void
 */
	public function connect() {
		if (!$this->connected()) {
			$this->_connect();
			$this->_auth();
		}
	}

/**
 * Check whether an open socket connection to the Telnet server is available.
 *
 * @return bool
 */
	public function connected() {
		return $this->_socket !== null && $this->_socket->connected;
	}

/**
 * Disconnect from the SMTP server.
 *
 * This method tries to disconnect only in case there is an open
 * connection available.
 *
 * @return void
 */
	public function disconnect() {
		if ($this->connected()) {
			$this->_disconnect();
		}
	}

/**
 * Check whether we have authenticated.
 *
 * @return bool
 */
	public function authenticated() {
		return $this->authenticated;
	}

/**
 * Send command
 *
 * @return void
 * @throws \NetDeviceLib\Net\Error\SocketException
 */
	public function execute( $cmd, $prompt='' ) {

		return $this->_socket->write( $cmd . $this->config('eol') );

	}

/**
 * Clears internal command buffer
 * FROM TelnetClient
 *
 * @return void
 */
    public function clearBuffer(){
        $this->_buffer = '';
    }  

/**
 * A simple wrapper for read that returns true or false
 *
 * @param string $prompt
 * @return boolean
 */
    public function readMatch( $prompt = NULL ) {
    	if ( !$this->read( $prompt ) ) {
    		return true;
    	}
    	return false;
    }

/**
 * Reads characters from the socket and adds them to command buffer.
 * Handles telnet control characters. Stops when prompt is ecountered.
 *
 * @param string $prompt
 * @return boolean
 */
    public function read( $prompt = NULL ){

    	if ( $prompt !== NULL ) {
    		$doMatch = true;
    	} else {
    		$doMatch = false;
    	}

        // clear the buffer
        $this->clearBuffer();

        $until_t = time() + $this->config('socket.timeout');

        do {

            // Throw Exception if socket timeout
            if( time() > $until_t ){
                throw new SocketException('Telnet timeout('.$this->config('socket.timeout').') reached while reading socket');
            }


            $c = $this->_socket->read(1);
            echo $c . "\t(".ord($c).")\n";
            flush();


			//Detect and Process Telnet Commands
			if ( $c == TEL_IAC ) {
				$this->_negotiateTelnetCommand();
				continue;
			}

            // End of read buffer, exit do loop
            if( $c === false ){
            	break;
            }

            // append current char to global buffer
            $this->_buffer .= $c;

            if ( $doMatch ) {
				if (preg_match('/('.$prompt.')/', $this->_buffer, $matches)) {
					//echo "Matched '$prompt' in buffer '''\n".$this->_buffer."\n'''\n";
					return $this->_buffer;
				} 
			}

        } while ( $c != TELOPT_BINARY || $c != TELOPT_XASCII );

        return $this->_buffer;
    }

	public function getCredentials() {
		return $this->_credentials;
	}
	
/**
 * Connect to SMTP Server
 *
 * @return void
 * @throws \NetDeviceLib\Net\Error\SocketException
 */
	protected function _connect() {
		$this->_generateSocket();
		if (!$this->_socket->connect()) {
			throw new SocketException('Unable to connect to Telnet server.');
		}
		
	}

/**
 * Send authentication
 *
 * @return void
 * @throws \NetDeviceLib\Net\Error\SocketException
 */
	protected function _auth() {

		if ($this->authenticated ) {
			return true;
		}


		//Expect <<prompt>> send <<command>><<cr><<lf>>
		$this->expect('ogin:')->send('figpal2');

		//Expect <<prompt>> send <<command>><<cr><<lf>> Expect <<failPrompt>> or <<successPrompt>>
		$this->expect('assword:')->send('figpal2');
			/*->fail('ogin failed', function($response) {
				throw new TelnetException('Authentication Failed');
			})
			->success('/\[(.*)@(.*)\]\s>/', function($response) {
				$this->authenticated = true;
			});*/

		//Expect <<prompt>> send <<command>><<cr><<lf>> read Data until <<prompt>> Return <<response>>-<<prompt>>
		//$config = $this->expect('>')->send('export')->readTo('>');
		//echo "\n\n\$config=\n" . $config . "\n\n\n";


		//Send <<command>><<cr><<lf>> read Data until <<prompt>> Return <<response>>-<<prompt>>
		$foo = $this->readTo('>');
		echo $foo;
		$this->send('/user print');
		$info = $this->readTo('>');
		echo $info;

		$this->send('/user print');
		$info = $this->readTo('>');
		echo $info;
		exit;

		//Send <<command>><<cr><<lf>> read Data until <<timeout>> exceeded
		//$info2 = $this->send('/system routerboard print')->read( $timeout = 2 );


		//end testing

		$this->read( $this->config('prompt.username') );
		$this->execute( $this->_credentials->getUsername() );
		//echo "Sent username\n";

		$this->read( $this->config('prompt.password') );
		$this->execute( $this->_credentials->getPassword() );
		//echo "Sent password\n";

		$response = $this->read( );
		if ( preg_match( '/(' . $this->config('prompt.noauth') . ')/', $response ) ) {
			return $this->authenticated = false;	
		}

		return $this->authenticated = true;

	}



/**
 * Disconnect
 *
 * @return void
 * @throws \NetDeviceLib\Net\Error\SocketException
 */
	protected function _disconnect() {
		foreach ( $this->config('commands.onDisconnect') as $cmd ) {
			$this->_socket->write( $cmd . $this->config('eol') );
		}

		$this->_socket->disconnect();
	}

/**
 * Expect (Chained Method)
 *
 * @return \NetDeviceLib\Net\Telnet\Client
 */
	public function expect( $matchPattern ) {

		//If no $matchPattern passed throw an exception
		if ( !$matchPattern ) {
			throw new \InvalidArgumentsException('expect called without pattern');
		}

		$this->_nextMatchPattern = $matchPattern;

		//determine type of match pattern, 'simple' or 'regex'
		if ( substr($matchPattern, 0 , 1) == '/') {
			$this->_nextMatchPatternType = 'regex';
		} else {
			$this->_nextMatchPatternType = 'simple';
		}

		echo "Debug:\tExpect ($matchPattern)\t\tPatternType=".$this->_nextMatchPatternType."\n";

		//Read to prompt, throw TelnetExpectException if prompt not encountered
		try {
			$this->readTo( $this->_nextMatchPattern );
		} catch( Exception $e ) {
			throw new TelnetExpectException("'".$this->_nextMatchPattern."' never received on socket");
		}

		return $this;
	}

/**
 * Send (Chained Method)
 *
 * @return \NetDeviceLib\Net\Telnet\Client
 */
	public function send( $command, $eol=null ) {

		//If no $matchPatter passed, assume Config(prompt.command)
		if ( !$eol ) {
			$eol = $this->config('eol');  
		}
		echo "Debug:\tSend( $command, \$eol=$eol )\n";

		//Write command to the socket
		$this->_socket->write ( $command . $eol );

		return $this;
	}

/**
 * Read To (Chained Method)
 *
 * @return \NetDeviceLib\Net\Telnet\Client
 */
	public function readTo( $matchPattern, $timeout=null ) {

		//If no $matchString passed, assume Config(prompt.command)
		//If no $matchPattern passed, assume Config(prompt.command)
		if ( !$timeout ) {
			$timeout = $this->config('readTimeout');  
		}
		echo "Debug:\treadTo( '$matchPattern', \$timeout=$timeout )\n";

		//determine type of match pattern, 'simple' or 'regex'
		if ( substr($matchPattern, 0 , 1) == '/') {
			$this->_nextMatchPatternType = 'regex';
		} else {
			$this->_nextMatchPatternType = 'simple';
		}

		$time_to_giveup = time() + $timeout;
		$this->clearBuffer();

		while( time() < $time_to_giveup ) {

            //Character read so extend timeout
            $time_to_giveup = time() + $timeout;

            $c = $this->_socket->read(1);

			//Detect and Process Telnet Commands
			if ( $c == TEL_IAC ) {
				$this->_negotiateTelnetCommand();
				continue;
			}

			$this->_buffer .= $c;

			if ( $this->_nextMatchPatternType == 'simple' ) {
				if ( strstr($this->_buffer, $matchPattern ) ) {
					echo "Debug:\tMatched(Simple) '".$matchPattern."'\n";
					return $this->_buffer;
				}
			}

			if ( $this->_nextMatchPatternType == 'regex' ) {
				if ( preg_match($matchPattern, $this->_buffer ) ) {
					echo "Debug:\tMatched(Regex) '".$matchPattern."'\n";
					return $this->_buffer;
				}
			}

		} // while()

		return $this->_buffer; //TODO trim $matchString from $this->_buffer
	}


/**
 * Helper method to generate socket
 *
 * @return void
 * @throws \NetDeviceLib\Net\Error\SocketException
 */
	protected function _generateSocket() {
		$this->_socket = new Socket($this->config('socket'));
	}

/**
 *
 * Negotiate Telnet Commands
 *
 */
	protected function _negotiateTelnetCommand() {
		//echo "\nnegotiateTelnetCommand \n";

		$c = $this->_socket->read(1);

		switch ( $c ) {
			case TEL_DO:
	            $opt = $this->_socket->read(1);
	            //echo "<<DO Option(" . ord($opt) . ")\n" ;
	            switch ( $opt ) {
	            	
	            	/*case TELOPT_TSPEED:
	            		echo "<<DO TSPEED\n";
	            		echo ">>WILL TSPEED\n";
	            		$this->_socket->write(TEL_IAC . TEL_WILL . $opt);
	            		break;*/
	            	default:
	            		//echo ">>WONT Option(" . ord($opt) . ")\n";
	            		$this->_socket->write(TEL_IAC . TEL_WONT . $opt);
	            }
				break;
			case TEL_DONT:
	            $opt = $this->_socket->read(1);
	            //echo "<<DONT Option(" . ord($opt) . ")\n" ;
	            switch ( $opt ) {

	            	default:
	            		//echo ">>WONT Option(" . ord($opt) . ")\n";
	            		$this->_socket->write(TEL_IAC . TEL_WONT . $opt);
	            }
				break;
			case TEL_WILL:
	            $opt = $this->_socket->read(1);
	            //echo "<<WILL Handle Option(" . ord($opt) . ")\n" ;
	            //echo ">>DONT Option(" . ord($opt) . ")\n";
	            $this->_socket->write(TEL_IAC . TEL_DONT . $opt);
	            break;
			case TEL_WONT:
	            $opt = $this->_socket->read(1);
	            //echo "<<WONT Option(" . ord($opt) . ")\n" ;
	            //echo ">>DONT Option(" . ord($opt) . ")\n";
	            $this->_socket->write(TEL_IAC . TEL_DONT . $opt);
	            break;

	        /*case TEL_SB:
	        	$opt = $this->_socket->read(1);
	        	switch ( $opt ) {
	        		case TELOPT_TSPEED: 
	        			echo "<<SB TSPEED(" . ord($opt) . ")\n";
	        			//echo "<< Next " . $this->_socket->read(1);
	        			echo "<< " . ord($this->_socket->read(1)) . "\n";
	        			echo "<< " . ord($this->_socket->read(1)) . "\n";
	        			echo "<< " . ord($this->_socket->read(1)) . "\n";
	        			echo ">> IAC TEL_SB TELOPT_TSPEED TEL_SE";
	        			break;	
	        	}
	        	
	        	break;*/

			default:
				throw new SocketException( "Unhandled Telnet Negotiation Command " . ord($c) );
		}
	}

}