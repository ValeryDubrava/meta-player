<?php
declare(ticks=1);

namespace {
    $mockSocketCreate = false;
    $mockSocketSelect = false;
    $mockSocketListen = false;
}
namespace Ding\Helpers\Tcp {
    function socket_create() {
        global $mockSocketCreate;
        if (isset($mockSocketCreate) && $mockSocketCreate === true) {
            return false;
        } else {
            return call_user_func_array('\socket_create', func_get_args());
        }
    }
    function socket_listen() {
        global $mockSocketListen;
        if (isset($mockSocketListen) && $mockSocketListen === true) {
            return false;
        } else {
            return call_user_func_array('\socket_listen', func_get_args());
        }
    }
    function socket_select(&$read1, &$write1, &$except1, $tv_sec, $tv_usec = 0) {
        global $mockSocketListen;
        if (isset($mockSocketListen) && $mockSocketListen === true) {
            return false;
        } else {
            $read2 = $read1;
            $write2 = $write1;
            $except2 = $except1;
            $r = \socket_select($read2, $write2, $except2, $tv_sec, $tv_usec);
            $read1 = $read2;
            $write1 = $write2;
            $except1 = $except2;
            return $r;
        }
    }
/**
 * This class will test the Tcp Client errors.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Tcp
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 *
 * Copyright 2011 Marcelo Gornstein <marcelog@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */
use Ding\Container\Impl\ContainerImpl;
use Ding\Helpers\Tcp\ITcpClientHandler;

/**
 * This class will test the Tcp Client errors.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Tcp
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_Tcp_Mock extends \PHPUnit_Framework_TestCase
{
    private $_properties = array();

    public function setUp()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
        $this->_properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'cache' => array(),
                'factory' => array(
                    'bdef' => array(
                        'xml' => array(
                        	'filename' => 'tcpclientmock.xml', 'directories' => array(RESOURCES_DIR)
                        )
                    )
                )
            )
        );
    }

    /**
     * @test
     * @expectedException \Ding\Helpers\Tcp\Exception\TcpException
     */
    public function cannot_socket_create()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = true;
        $mockSocketSelect = false;
        $mockSocketListen = false;
        $container = ContainerImpl::getInstance($this->_properties);
        $client = $container->getBean('Client2');
        $client->open();
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
    }

    /**
     * @test
     * @expectedException \Ding\Helpers\Tcp\Exception\TcpException
     */
    public function cannot_socket_select()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
        $container = ContainerImpl::getInstance($this->_properties);
        $client = $container->getBean('Client2');
        $client->open();
        while (MyClientHandler::$time < 1) {
            usleep(1000);
        }
        unregister_tick_function(array($client, 'process'));
        $mockSocketListen = true;
        $client->process();
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
    }

    /**
     * @test
     * @expectedException \Ding\Helpers\Tcp\Exception\TcpException
     */
    public function cannot_socket_select_on_server()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
        $container = ContainerImpl::getInstance($this->_properties);
        $server = $container->getBean('Server');
        $server->open();
        unregister_tick_function(array($server, 'process'));
        $mockSocketListen = true;
        $server->process();
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
    }

    /**
     * @test
     * @expectedException \Ding\Helpers\Tcp\Exception\TcpException
     */
    public function cannot_socket_select_on_server_peers()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
        $container = ContainerImpl::getInstance($this->_properties);
        $server = $container->getBean('Server');
        $server->open();
        $client = $container->getBean('Client6');
        $client->open();
        while (MyClientHandler::$time < 1) {
            usleep(1000);
        }
        unregister_tick_function(array($server, 'process'));
        unregister_tick_function(array($client, 'process'));
        $mockSocketListen = true;
        $server->processPeers();
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
    }

    /**
     * @test
     * @expectedException \Ding\Helpers\Tcp\Exception\TcpException
     */
    public function cannot_socket_create_on_server()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = true;
        $mockSocketListen = false;
        $mockSocketSelect = false;
        $container = ContainerImpl::getInstance($this->_properties);
        $client = $container->getBean('Server');
        $client->open();
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
    }
    /**
     * @test
     * @expectedException \Ding\Helpers\Tcp\Exception\TcpException
     */
    public function cannot_socket_listen_on_server()
    {
        global $mockSocketCreate;
        global $mockSocketSelect;
        global $mockSocketListen;
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = true;
        $container = ContainerImpl::getInstance($this->_properties);
        $client = $container->getBean('Server');
        $client->open();
        $mockSocketCreate = false;
        $mockSocketSelect = false;
        $mockSocketListen = false;
    }
}

class MyClientHandler implements ITcpClientHandler
{
    public static $time;
    protected $client;
    public static $data;

    public function connectTimeout()
    {
        self::$time = time();
    }

    public function readTimeout()
    {
        self::$time = time();
    }
    public function beforeConnect()
    {
    }

    public function connect()
    {
        self::$time = time();
    }

    public function disconnect()
    {
    }

    public function setClient(\Ding\Helpers\Tcp\TcpClientHelper $client)
    {
        $this->client = $client;
    }

    public function data()
    {
        $buffer = '';
        $len = 4096;
        $this->client->read($buffer, $len);
        self::$data = $buffer;
        $this->client->close();
    }
}
class MyServerHandler implements ITcpServerHandler
{
    public static $data;

    public function beforeOpen()
    {
    }

    public function beforeListen()
    {
    }

    public function close()
    {
    }

    public function handleConnection(\Ding\Helpers\Tcp\TcpPeer $peer)
    {
        $peer->write("Hi!\n");
    }

    public function readTimeout(\Ding\Helpers\Tcp\TcpPeer $peer)
    {
        self::$data = 'timeout';
    }

    public function handleData(\Ding\Helpers\Tcp\TcpPeer $peer)
    {
        $buffer = '';
        $len = 1024;
        $peer->read($buffer, $len);
        self::$data = $buffer;
    }

    public static function doClient($client)
    {
        $client->open();
        sleep(2);
    }

    public function disconnect(\Ding\Helpers\Tcp\TcpPeer $peer)
    {
    }
}
}
