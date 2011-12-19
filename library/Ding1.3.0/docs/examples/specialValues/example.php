<?php
/**
 * Example using ding. See also beans.xml.
 *
 * PHP Version 5
 *
 * @category   Ding
 * @package    Examples
 * @subpackage Basic
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
ini_set(
    'include_path',
    implode(
        PATH_SEPARATOR,
        array(
            implode(DIRECTORY_SEPARATOR, array('..', '..', '..', 'src', 'mg')),
            ini_get('include_path'),
        )
    )
);

/**
 * This is our bean.
 */
class MyBean
{
    private $_trueProperty;
    private $_falseProperty;
    private $_nullProperty;

    public function setTrueProperty($value)
    {
        if ($value !== true) {
            throw new Exception('Not true.');
        }
        $this->_trueProperty = $value;
    }

    public function setFalseProperty($value)
    {
        if ($value !== false) {
            throw new Exception('Not false.');
        }
        $this->_falseProperty = $value;
    }

    public function setNullProperty($value)
    {
        if ($value !== null) {
            throw new Exception('Not null.');
        }
        $this->_nullProperty = $value;
    }

    public function getTrueProperty()
    {
        return $this->_trueProperty;
    }

    public function getFalseProperty()
    {
        return $this->_falseProperty;
    }

    public function getNullProperty()
    {
        return $this->_nullProperty;
    }

    public function __construct()
    {
        echo 'Got: ';
        var_dump(func_get_args());
    }
}
require_once 'Ding/Autoloader/Autoloader.php'; // Include ding autoloader.
\Ding\Autoloader\Autoloader::register(); // Call autoloader register for ding autoloader.
use Ding\Container\Impl\ContainerImpl;

// Here you configure the container, its subcomponents, drivers, etc.
$properties = array(
    'ding' => array(
        'log4php.properties' => './log4php.properties',
        'factory' => array(
            'bdef' => array( // Both of these drivers are optional. They are both included just for the thrill of it.
                'xml' => array('filename' => 'beans.xml'),
                'annotation' => array('scanDir' => array(realpath(__DIR__)))
            ),
        ),
        // You can configure the cache for the bean definition, the beans, and the proxy definitions.
        // Other available implementations: zend, file, dummy, and memcached.
    	'cache' => array(
            'proxy' => array('impl' => 'dummy'),
            'bdef' => array('impl' => 'dummy'),
            'beans' => array('impl' => 'dummy')
        )
    )
);
$container = ContainerImpl::getInstance($properties);
$bean = $container->getBean('myBeanName');
var_dump($bean->getTrueProperty());
var_dump($bean->getNullProperty());
var_dump($bean->getFalseProperty());
