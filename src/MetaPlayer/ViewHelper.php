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

namespace MetaPlayer;

/**
 * The ViewHelper provides utilitary methods for rendering view.
 *
 * @author Val Dubrava <valery.dubrava@gmail.com>
 */
class ViewHelper 
{
    private static $dateFormat = "Y-m-d";
    private static $timeFormat = "G:i:s";
    
    /**
     * Formats date form the specified datetime to string.
     * 
     * @param \DateTime $date
     * @return string
     */
    public static function formatDate(\DateTime $date) {
        return $date->format(self::$dateFormat);
    }
    
    /**
     * Formats the specified datetime to string.
     *
     * @param \DateTime $date
     * @return string
     */
    public static function formatDateTime(\DateTime $date) {
        return $date->format(self::$dateFormat . " " . self::$timeFormat);
    }
    
    /**
     * Formats time form the specified datetime to string.
     *
     * @param \DateTime $date
     * @return string
     */
    public static function formatTime(\DateTime $date) {
        return $date->format(self::$timeFormat);
    }
    
}