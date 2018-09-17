<?php

namespace JS\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="`user`")
 * @ORM\Entity(repositoryClass="JS\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser {

    /**
     * @ORM\OneToMany(targetEntity="JS\MemeBundle\Entity\Score", mappedBy="user", cascade={"persist"})
     */
    private $scores; // Plural is not an error, an user has many scores


    /**
     * @ORM\OneToMany(targetEntity="JS\MemeBundle\Entity\Meme", mappedBy="user", cascade={"persist"})
     */
    private $memes; // Plural is not an error, an user has many scores


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    public function __construct() {
        parent::__construct();

        $this->scores = new ArrayCollection();
        $this->memes = new ArrayCollection();
    }


    /**
     * Add score
     *
     * @param \JS\MemeBundle\Entity\Score $score
     *
     * @return User
     */
    public function addScore(\JS\MemeBundle\Entity\Score $score)
    {
        $this->scores[] = $score;

        $score->setUser($this);

        return $this;
    }

    /**
     * Remove score
     *
     * @param \JS\MemeBundle\Entity\Score $score
     */
    public function removeScore(\JS\MemeBundle\Entity\Score $score)
    {
        $this->scores->removeElement($score);
    }

    /**
     * Get scores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScores()
    {
        return $this->scores;
    }

    /**
     * Add meme
     *
     * @param \JS\MemeBundle\Entity\Meme $meme
     *
     * @return User
     */
    public function addMeme(\JS\MemeBundle\Entity\Meme $meme)
    {
        $this->memes[] = $meme;

        $meme->setUser($this);

        return $this;
    }

    /**
     * Remove meme
     *
     * @param \JS\MemeBundle\Entity\Meme $meme
     */
    public function removeMeme(\JS\MemeBundle\Entity\Meme $meme)
    {
        $this->memes->removeElement($meme);
    }

    /**
     * Get memes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMemes()
    {
        return $this->memes;
    }
}
