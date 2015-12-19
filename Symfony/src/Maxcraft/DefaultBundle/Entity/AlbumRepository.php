<?php

namespace Maxcraft\DefaultBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
 * AlbumRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlbumRepository extends EntityRepository{

    public function findImages(Album $album){
        return $this->getEntityManager()->getRepository('MaxcraftdefaultBundle:Image')->findBy(
            array('album'=> $album->getId()),
            array('id'=> 'desc'),
            null,
            0
        );
    }
}
