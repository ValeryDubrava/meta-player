<?php

/*
 * MetaPlayer 1.0
 *  
 * Licensed under the GPL terms
 * To use it on other terms please contact us
 * 
 * Copyright(c) 2010-2011 Val Dubrava [ valery.dubrava@gmail.com ] 
 *  
 */
namespace MetaPlayer\Controller;

use Ding\Logger\ILoggerAware;
use Ding\Mvc\ModelAndView;
use Oak\MVC\JsonViewModel;
use Oak\Json\JsonUtils;
/**
 * Description of ExceptionController
 *
 * @Controller
 * @RequestMapping(url=/Exception)
 * @Component(name={ExceptionController, exceptionController})
 * @Scope(value=singleton)
 * 
 * @author Val Dubrava <valery.dubrava@gmail.com>
 */
class ExceptionController implements ILoggerAware
{
    /**
     * @var \Logger
     */
    private $logger;

    private $headers = array('HTTP/1.1 500 Internal Server Error', 'Status: 500 Internal Server Error');
    
    /**
     * @Resource
     * @var JsonUtils
     */
    private $jsonUtils;

    /**
     * @param \Exception $exception
     * @return \Ding\Mvc\ModelAndView
     */
    public function _ExceptionException($exception)
    {
        $this->logger->error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString(), $exception);

        return new ModelAndView("Exception/exception", array('headers' => $this->headers, 'exception' => $exception));
    }
    
    public function metaPlayer_JsonExceptionAction(\MetaPlayer\JsonException $exception) {
        $this->logger->error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString(), $exception);

        return new JsonViewModel(
                new \MetaPlayer\Contract\ExceptionDto($exception->getMessage(),
                $exception->getCode()),
                $this->jsonUtils,
                $this->headers);
    }

    public function setLogger(\Logger $logger) {
        $this->logger = $logger;
    }
}

