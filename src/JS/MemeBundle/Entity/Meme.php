<?php

namespace JS\MemeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Meme
 *
 * @ORM\Table(name="meme")
 * @ORM\Entity(repositoryClass="JS\MemeBundle\Repository\MemeRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 *
 * @Vich\Uploadable()
 */
class Meme
{

    /**
     * @ORM\OneToMany(targetEntity="JS\MemeBundle\Entity\Score", mappedBy="meme")
     */
    private $scores; // Plural is not an error, an user has many scores

    /**
     * @ORM\ManyToOne(targetEntity="JS\UserBundle\Entity\User", inversedBy="memes", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="JS\CategoryBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    private $category;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\Length(max="255", maxMessage="Title must be at least {{ limit }} characters")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     *
     * @Assert\Url(protocols={"http", "https"})
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;
    
    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="meme_image", fileNameProperty="uri")
     * @Assert\File(maxSize="3M", mimeTypes={"image/gif", "image/png", "image/jpeg"})
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_comment", type="integer", options={"default"="0"})
     */
    private $nbComment = 0;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nb_point", type="integer", options={"default"="0"})
     */
    private $nbPoint = 0;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $updatedAt;
    
    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;
    
    public function __construct() {
        $this->scores = new ArrayCollection();
    }
    
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
     * Set title
     *
     * @param string $title
     *
     * @return Meme
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Meme
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return Meme
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
        
        return $this;
    }
    
    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
    
    /**
     * Get file
     *
     * @return mixed
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @throws \Exception
     */
    public function setFile(?File $image = null): void
    {
        $this->file = $image;

        if (null !== $image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Meme
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Meme
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Meme
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Meme
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
        
        return $this;
    }
    
    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
    
    /**
     * Set nbComment
     *
     * @param integer $nbComment
     *
     * @return Meme
     */
    public function setNbComment($nbComment)
    {
        $this->nbComment = $nbComment;
        
        return $this;
    }
    
    /**
     * Get nbComment
     *
     * @return integer
     */
    public function getNbComment()
    {
        return $this->nbComment;
    }
    
    /**
     * Set nbPoint
     *
     * @param integer $nbPoint
     *
     * @return Meme
     */
    public function setNbPoint($nbPoint)
    {
        $this->nbPoint = $nbPoint;
        
        return $this;
    }
    
    /**
     * Get nbPoint
     *
     * @return integer
     */
    public function getNbPoint()
    {
        return $this->nbPoint;
    }

    /**
     * Set category
     *
     * @param \JS\CategoryBundle\Entity\Category $category
     *
     * @return Meme
     */
    public function setCategory(\JS\CategoryBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \JS\CategoryBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function autoSetDates(){
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function autoUpdateDate(){
        $this->setUpdatedAt(new \DateTime());
    }

    public function autoIncreaseNbComment(){
        $this->nbComment++;
    }
    
    public function autoDecreaseNbComment(){
        $this->nbComment--;
    }

    public function autoIncreaseNbPoint(){
        $this->nbPoint++;
    }
    
    public function autoDecreaseNbPoint(){
        $this->nbPoint--;
    }

    /**
     * Set user
     *
     * @param \JS\UserBundle\Entity\User $user
     *
     * @return Meme
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
     * Add score
     *
     * @param \JS\MemeBundle\Entity\Score $score
     *
     * @return Meme
     */
    public function addScore(\JS\MemeBundle\Entity\Score $score)
    {
        $this->scores[] = $score;

        $score->setMeme($this);

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

    public function __toString()
    {
        return $this->title;
    }
}
