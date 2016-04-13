<?php
    namespace AppBundle\Entity;

    use AppBundle\Entity\Booking\Book;
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
         * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Booking\Book", inversedBy="book")
         * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
         */
        private $book;

        /**
         * Set book
         *
         * @param Book $book
         *
         * @return User
         */
        public function setBook(Book $book = null)
        {
            $this->book = $book;

            return $this;
        }

        /**
         * Get book
         *
         * @return Book
         */
        public function getBook()
        {
            return $this->book;
        }
    }