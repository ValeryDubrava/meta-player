<?php
/**
 * This class will test the rendered view.
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
use Ding\HttpSession\HttpSession;
use Ding\Container\Impl\ContainerImpl;
use Ding\Mvc\ModelAndView;

/**
 * This class will test the rendered view.
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
class Test_HttpView extends PHPUnit_Framework_TestCase
{
    private $_properties = array();

    public function setUp()
    {
        $this->_properties = array(
            'ding' => array(
                'log4php.properties' => RESOURCES_DIR . DIRECTORY_SEPARATOR . 'log4php.properties',
                'factory' => array(
                    'properties' => array('prefix' => RESOURCES_DIR),
                    'bdef' => array(
                        'xml' => array('filename' => 'mvc.xml', 'directories' => array(RESOURCES_DIR))
                    )
                )
            )
        );
    }

    /**
     * @test
     */
    public function can_render_and_translate()
    {
        // For language stuff
        ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . RESOURCES_DIR);
        $this->expectOutputString('hi there');
        $mav = new ModelAndView('some');
        $mav->add(array('headers' => array('HTTP/1.0 404 Not Found')));
        $container = ContainerImpl::getInstance($this->_properties);
        $resolver = $container->getBean('HttpViewResolver3');
        $view = $resolver->resolve($mav);
        $render = $container->getBean('HttpViewRender');
        $render->render($view);
        $this->assertEquals(
            $render->translate(
            	'abundle', 'message.some',
                array('arg1' => '1', 'arg2' => '2', 'arg3' => '3')
            ),
            'This is a test message, arg1=1 arg2=2 arg3=3'
        );
        $session = HttpSession::getSession();
        $session->setAttribute('LANGUAGE', 'es_AR');
        $this->assertEquals(
            $render->translate(
            	'abundle', 'message.some',
                array('arg1' => '1', 'arg2' => '2', 'arg3' => '3')
            ),
            'Este es un mensaje de prueba, arg1=1 arg2=2 arg3=3'
        );
    }
}
;