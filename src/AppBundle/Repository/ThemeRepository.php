<?php

namespace AppBundle\Repository;

/**
 * ThemeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ThemeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getThemeById($id)
    {
       
       $qb = $this
         ->createQueryBuilder('t')
         ->leftJoin('t.messages', 'msg')
         ->addSelect('msg')
         ->where('t.id = ?1')
         ->setParameter(1, $id)
       ;
     
       return $qb
         ->getQuery()
         ->getResult()
       ;
    }
}