<?php
declare(ticks=1);
/**
 * This class will test the signal handler driver.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Signal
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

/**
 * This class will test the signal handler driver.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Signal
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_Signal extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function can_set_signal_handler()
    {
        $properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'factory' => array(
                    'bdef' => array(
                        'xml' => array('filename' => 'signalBeans.xml', 'directories' => array(RESOURCES_DIR))
                    )
                )
            )
        );
        $container = ContainerImpl::getInstance($properties);
        posix_kill(posix_getpid(), SIGUSR1);
        $this->assertTrue(MySignalHandler::$something);
    }

    /**
     * @test
     */
    public function can_set_annotated_signal_handler()
    {
        $properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'factory' => array(
                    'bdef' => array(
                        'annotation' => array('scanDir' => array(__DIR__))
                    )
                )
            )
        );
        $container = ContainerImpl::getInstance($properties);
        posix_kill(posix_getpid(), SIGUSR1);
        $this->assertTrue(MySignalHandler2::$something);
    }

    /**
     * @test
     */
    public function can_do_nothing_if_no_handlers()
    {
        $properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'factory' => array(
                )
            )
        );
        $container = ContainerImpl::getInstance($properties);
    }
}

class MySignalHandler
{
    public static $something = null;

    public function onDingSignal($signal)
    {
        self::$something = true;
    }
}

/**
 * @Component
 * @ListensOn(value=dingSignal)
 */
class MySignalHandler2
{
    public static $something = null;

    public function onDingSignal($signal)
    {
        self::$something = true;
    }
}
