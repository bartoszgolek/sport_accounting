<?php

namespace AppBundle\Repository;


use AppBundle\Entity\Member;
use Doctrine\ORM\EntityRepository;

/**
 * MemberRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MemberRepository extends EntityRepository
{
    /**
     * @param Member $member
     */
    public function save(Member $member)
    {
        $this->_em->persist($member);
        $this->_em->flush();
    }

    /**
     * @param Member $member
     */
    public function delete(Member $member)
    {
        $this->_em->remove($member);
        $this->_em->flush();
    }
}
