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

namespace MetaPlayer\Repository;

use Doctrine\ORM\EntityManager;

/**
 * The class RepositoryFactory construct all repositories.
 *
 * @author Val Dubrava <valery.dubrava@gmail.com>
 * @Configuration
 */
class RepositoriesFactory
{
    /**
     * @Resource
     * @var EntityManager
     */
    private $entityManager;


    /**
     * @Bean(name={bandRepository, BandRepository})
     * @return BandRepository
     */
    public function getBandRepository() {
        return $this->entityManager->getRepository('MetaPlayer\Model\Band');
    }

    /**
     * @Bean(name={albumRepository, AlbumRepository})
     * @return AlbumRepository
     */
    public function getAlbumRepository() {
        return $this->entityManager->getRepository('MetaPlayer\Model\Album');
    }

    /**
     * @Bean(name={trackRepository, TrackRepository})
     * @return TrackRepository
     */
    public function getTrackRepository() {
        return $this->entityManager->getRepository('MetaPlayer\Model\Track');
    }

}