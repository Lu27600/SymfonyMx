<?php

namespace Maxcraft\DefaultBundle\Entity;
use Doctrine\ORM\EntityRepository;

/**
 * ZoneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ZoneRepository extends EntityRepository
{
    public function findBuilders($zoneId){
        return $this->getEntityManager()->getRepository('MaxcraftDefaultBundle:Builder')->findBy(
            array('zone_id' => $zoneId, 'role' => 'BUILD'),
            null,
            null,
            0
        );
    }

    public function findCuboiders($zoneId){
        return $this->getEntityManager()->getRepository('MaxcraftDefaultBundle:Builder')->findBy(
            array('zone_id' => $zoneId, 'role' => 'CUBO'),
            null,
            null,
            0
        );
    }

    /**
     * @param Zone $zone
     * @return string
     */
    public  function objectToString(Zone $zone){

        $builderslist = null;
        foreach($this->findBuilders($zone->getId()) as $builder) $builderslist = $builderslist.';'.$builder->getUser()->getUuid();
        if ($builderslist[strlen($builderslist)-1]==';') $builderslist[strlen($builderslist)-1]=null;
        if ($builderslist[0]==';') $builderslist[0]=null;

        $cuboiderslist = null;
        foreach ($this->findCuboiders($zone->getId()) as $cuboider) $cuboiderslist = $cuboiderslist . ';' .$cuboider->getUser()->getUuid();
        if ($cuboiderslist[strlen($cuboiderslist)-1]==';') $cuboiderslist[strlen($cuboiderslist)-1]=null;
        if ($cuboiderslist[0]==';') $cuboiderslist[0]=null;


        $id = "id=".'"'.$zone->getId().'",';
        if($zone->getName()== null){$name = 'name="null",';} else{$name = "name=".'"'.$zone->getName().'",';}
        if($zone->getParent()==null){$parent = 'parent="null",';} else{$parent = "parent=".'"'.$zone->getParent()->getId().'",';}
        $points = "points=".'"'.$zone->getPoints().'",';
        if ($zone->getOwner()==null){$owner = 'owner="null",';} else{$owner = "owner=".'"'.$zone->getOwner()->getUuid().'",';}
        $world = 'world="'.$zone->getWorld().'",';
        if ($zone->getTags()==null) { $tags='tags="null",';} else{$tags="tags=".'"'.$zone->getTags().'",';}
        if ($builderslist==null) { $builders='builders="null",';} else{$builders="builders=".'"'.$builderslist.'",';}
        if ($cuboiderslist==null) {$cuboiders='cuboiders="null",';} else{$cuboiders="cuboiders=".'"'.$cuboiderslist.'"';}

        $str = "-zone:".$id.$name.$parent.$points.$owner.$world.$tags.$builders.$cuboiders;
        strval($str);
        if ($str[strlen($str)-1]==',') $str[strlen($str)-1]=null;
        return $str;
    }

}