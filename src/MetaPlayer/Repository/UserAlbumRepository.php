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

use Doctrine\ORM\Mapping\ClassMetadata;
use MetaPlayer\Model\UserAlbum;
use MetaPlayer\Model\User;

/**
 * The class UserAlbumRepository represents repository of the UserAlbum.
 *
 * @author Val Dubrava <valery.dubrava@gmail.com>
 */
class UserAlbumRepository extends BaseRepository {
    public function __construct($em, ClassMetadata $class) {
        parent::__construct($em, $class);
        
        // $class->rootEntityName = $class->name;
    }

    /**
     * @param \MetaPlayer\Model\User $user
     * @param $userBandId
     * @return \MetaPlayer\Model\UserAlbum[]
     */
    public function findByUserAndBand(User $user, $userBandId) {
        return $this->findBy(array('user' => $user, 'userBand' => $userBandId));
    }

    /**
     * @param $entity
     * @return UserAlbumRepository
     * @throws \MetaPlayer\MetaPlayerException
     */
    public function remove($entity) {
        if ($entity->isApproved()) {
            throw new \MetaPlayer\MetaPlayerException("Impossible to remove approved user entity.");
        }
        return parent::remove($entity);
    }
}