<?php

namespace JS\MemeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="score")
 * @ORM\Entity(repositoryClass="JS\MemeBundle\Repository\ScoreRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Score
{
    /**
     * @ORM\ManyToOne(targetEntity="JS\MemeBundle\Entity\Meme", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $meme;

    /**
     * @ORM\ManyToOne(targetEntity="JS\UserBundle\Entity\User", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="point", type="smallint")
     */
    private $point;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set point
     *
     * @param integer $point
     *
     * @return Score
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set meme
     *
     * @param \JS\MemeBundle\Entity\Meme $meme
     *
     * @return Score
     */
    public function setMeme(\JS\MemeBundle\Entity\Meme $meme)
    {
        $this->meme = $meme;

        return $this;
    }

    /**
     * Get meme
     *
     * @return \JS\MemeBundle\Entity\Meme
     */
    public function getMeme()
    {
        return $this->meme;
    }

    /**
     * Set user
     *
     * @param \JS\UserBundle\Entity\User $user
     *
     * @return Score
     */
    public function setUser(\JS\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \JS\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @ORM\PrePersist()
     */
    public function autoIncreaseNbPoint(){
        if($this->getPoint() > 0){
            $this->getMeme()->autoIncreaseNbPoint();
        }
        else{
            $this->getMeme()->autoDecreaseNbPoint();
        }
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function autoDecreaseNbPoint(){
        if($this->getPoint() > 0){
            $this->getMeme()->autoDecreaseNbPoint();
        }
        else{
            $this->getMeme()->autoIncreaseNbPoint();
        }
    }

    public function __toString()
    {
        return $this->user->getUsername();
    }
}
