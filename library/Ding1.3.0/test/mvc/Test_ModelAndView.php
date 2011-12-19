<?php
/**
 * This class will test the ModelAndview.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage mvc
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

use Ding\Mvc\ModelAndView;
use Ding\Mvc\RedirectModelAndView;
use Ding\Mvc\ForwardModelAndView;
use Ding\Container\Impl\ContainerImpl;

/**
 * This class will test the ModelAndview.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Test
 * @subpackage mvc
 * @author     Marcelo Gornstein <marcelog@gmail.com>
 * @license    http://marcelog.github.com/ Apache License 2.0
 * @link       http://marcelog.github.com/
 */
class Test_ModelAndView extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function can_get_set_model()
    {
        $mav = new ModelAndView('name');
        $mav->add(array('key1' => 'value1'));
        $model = $mav->getModel();
        $this->assertEquals($model['key1'], 'value1');
    }

    /**
     * @test
     */
    public function can_redirect_mav()
    {
        $mav = new RedirectModelAndView('name');
        $mav->add(array('key1' => 'value1'));
        $model = $mav->getModel();
        $this->assertEquals($model['key1'], 'value1');
    }

    /**
     * @test
     */
    public function can_forward_mav()
    {
        $mav = new ForwardModelAndView('name');
        $mav->add(array('key1' => 'value1'));
        $model = $mav->getModel();
        $this->assertEquals($model['key1'], 'value1');
    }
}