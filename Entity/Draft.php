<?php

namespace Duf\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Model\DufAdminUserInterface;

/**
 * Draft
 *
 * @ORM\Table(name="draft")
 * @ORM\Entity(repositoryClass="Duf\MessagingBundle\Entity\Repository\DraftRepository")
 */
class Draft extends DufAdminEntity implements DufAdminUserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Duf\AdminBundle\Model\DufAdminUserInterface")
     * @ORM\JoinColumn(nullable=false)
     * @var DufAdminUserInterface
     */
     protected $author;

    /**
     * @ORM\OneToMany(targetEntity="Duf\MessagingBundle\Entity\DraftUser", orphanRemoval=true, mappedBy="draft", cascade={"persist","remove"})
     */
     private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set subject
     *
     * @param string $subject
     *
     * @return Draft
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set author
     *
     * @param \Duf\AdminBundle\Model\DufAdminUserInterface $author
     *
     * @return Message
     */
    public function setAuthor(\Duf\AdminBundle\Model\DufAdminUserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Duf\AdminBundle\Model\DufAdminUserInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Add user
     *
     * @param \Duf\MessagingBundle\Entity\DraftUser $user
     *
     * @return Message
     */
    public function addUser(\Duf\MessagingBundle\Entity\DraftUser $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Duf\MessagingBundle\Entity\DraftUser $user
     */
    public function removeUser(\Duf\MessagingBundle\Entity\DraftUser $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function getMessageIntro($limit = 70)
    {
        $intro      = strip_tags($this->getContent());
        return substr($intro, 0, $limit);
    }
}
