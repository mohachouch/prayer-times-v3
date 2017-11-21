<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository {

    /**
     * 
     * @param string $search
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    function search($search) {
        $qb = $this->createQueryBuilder("u")
                ->orderBy("u.id", "DESC");

        if (!empty($search)) {
            $qb->andWhere("u.username LIKE :search "
                            . "OR u.email LIKE :search"
                    )
                    ->setParameter(":search", "%$search%");
        }
        return $qb;
    }

}
