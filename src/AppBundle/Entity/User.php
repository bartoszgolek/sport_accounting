<?php
    namespace AppBundle\Entity;

    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Table(name="fos_user")
     * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
     */
    class User extends BaseUser
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        public function __construct()
        {
            parent::__construct();
        }

        /**
         * @var Member
         *
         * @ORM\ManyToOne(targetEntity="Member")
         * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
         */
        private $member;

        /**
         * Set member
         *
         * @param Member $member
         *
         * @return $this
         */
        public function setMember(Member $member = null)
        {
            $this->member = $member;

            return $this;
        }

        /**
         * Get member
         *
         * @return Member
         */
        public function getMember()
        {
            return $this->member;
        }
    }