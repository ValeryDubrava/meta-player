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

namespace MetaPlayer\Model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Base entity of Album which implements common behaviour.
 * @author Val Dubrava <valery.dubrava@gmail.com>
 */
abstract class BaseAlbum implements IIdentified
{
    /**
     * @Id @Column(type="bigint")
     * @GeneratedValue
     * @var int
     */
    protected $id;

    /**
     * @Column
     * @var string
     */
    protected $title;
    /**
     * @Column(type="date", name="release_date")
     * @var \DateTime
     */
    protected $releaseDate;

    /**
     * @param $title
     * @param \DateTime $releaseDate
     */
    public function __construct($title, \DateTime $releaseDate) {
        $this->title = $title;
        $this->releaseDate = $releaseDate;
    }

    public function getId() {
        return $this->id;
    }
    
    /**
     * @return BaseBand
     */
    public abstract function getBand();

    /**
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return \DateTime
     */
    public function getReleaseDate() {
        return $this->releaseDate;
    }

    /**
     * @param \DateTime $releaseDate
     */
    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
