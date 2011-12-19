<?php
/**
 * This class will test the autoloader.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Autoloader
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

/**
 * This class will test the autoloader.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage Autoloader
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_Autoloader extends PHPUnit_Framework_TestCase
{
    public function dummyAutoloader()
    {
        return true;
    }

    /**
     * @test
     */
    public function can_register()
    {
        \Ding\Autoloader\Autoloader::register(); // Call autoloader register for ding autoloader.
        spl_autoload_register(array($this, 'dummyAutoloader'));
        $this->assertFalse(class_exists('A\B\C', true));
    }
}
