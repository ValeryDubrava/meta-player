<?php
/**
 * This class will test the shutdown handler driver.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Shutdown
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
 * This class will test the shutdown handler driver.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Shutdown
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_Shutdown extends PHPUnit_Framework_TestCase
{
    public static $tempFile = '/tmp/DingTestForShutdownDriver';

    /**
     * @test
     */
    public function can_set_shutdown_handler()
    {
        @unlink(self::$tempFile);
        touch(self::$tempFile);
        $this->assertFileExists(self::$tempFile);
        $properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'factory' => array(
                    'bdef' => array(
                        'xml' => array('filename' => 'shutdownBeans.xml', 'directories' => array(RESOURCES_DIR))
                    )
                )
            )
        );
        $container = ContainerImpl::getInstance($properties);
    }

    /**
     * @test
     */
    public function can_call_shutdown()
    {
        $this->assertFileNotExists(self::$tempFile);
    }

    /**
     * @test
     */
    public function can_set_annotated_shutdown_handler()
    {
        @unlink(self::$tempFile);
        touch(self::$tempFile);
        $this->assertFileExists(self::$tempFile);
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
    }

    /**
     * @test
     */
    public function can_call_annotated_shutdown()
    {
        $this->assertFileNotExists(self::$tempFile);
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

class MyShutdownHandler
{
    public function onDingShutdown()
    {
        unlink(Test_Shutdown::$tempFile);
    }
}

/**
 * @Component
 * @ListensOn(value=dingShutdown)
 */
class MyShutdownHandler2
{
    public function onDingShutdown()
    {
        unlink(Test_Shutdown::$tempFile);
    }
}