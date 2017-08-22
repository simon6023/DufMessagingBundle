<?php

namespace Duf\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Model\DufAdminUserInterface;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Duf\MessagingBundle\Entity\Repository\MessageRepository")
 */
class Message extends DufAdminEntity implements DufAdminUserInterface
{
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
     * @ORM\ManyToOne(targetEntity="Duf\MessagingBundle\Entity\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
     protected $conversation;

    /**
     * @ORM\OneToMany(targetEntity="Duf\MessagingBundle\Entity\MessageUser", orphanRemoval=true, mappedBy="message", cascade={"persist","remove"})
     */
     private $users;

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
     * Set conversation
     *
     * @param \Duf\MessagingBundle\Entity\Conversation $conversation
     *
     * @return Message
     */
    public function setConversation(\Duf\MessagingBundle\Entity\Conversation $conversation)
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \Duf\MessagingBundle\Entity\Conversation
     */
    public function getConversation()
    {
        return $this->conversation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Duf\MessagingBundle\Entity\MessageUser $user
     *
     * @return Message
     */
    public function addUser(\Duf\MessagingBundle\Entity\MessageUser $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Duf\MessagingBundle\Entity\MessageUser $user
     */
    public function removeUser(\Duf\MessagingBundle\Entity\MessageUser $user)
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
}
