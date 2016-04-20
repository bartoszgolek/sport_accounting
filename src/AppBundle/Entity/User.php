<?php
    namespace AppBundle\Entity;

    use AppBundle\Entity\Player;
    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="fos_user")
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
         * @var int
         *
         * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Player")
         * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
         */
        private $player;

        /**
         * Set player
         *
         * @param Player $player
         *
         * @return User
         */
        public function setPlayer(Player $player = null)
        {
            $this->player = $player;

            return $this;
        }

        /**
         * Get player
         *
         * @return Player
         */
        public function getPlayer()
        {
            return $this->player;
        }
    }