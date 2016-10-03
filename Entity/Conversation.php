<?php

namespace Duf\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;

/**
 * Conversation
 *
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="Duf\MessagingBundle\Entity\Repository\ConversationRepository")
 */
class Conversation extends DufAdminEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\OneToMany(targetEntity="Duf\MessagingBundle\Entity\ConversationUser", orphanRemoval=true, mappedBy="conversation", cascade={"persist","remove"})
     */
     private $users;

    /**
     * @ORM\OneToMany(targetEntity="Duf\MessagingBundle\Entity\Message", orphanRemoval=true, mappedBy="conversation", cascade={"persist","remove"})
     * @ORM\OrderBy({"created_at" = "ASC"})
     */
     private $messages;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Conversation
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
     * Add user
     *
     * @param \Duf\MessagingBundle\Entity\ConversationUser $user
     *
     * @return Conversation
     */
    public function addUser(\Duf\MessagingBundle\Entity\ConversationUser $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Duf\MessagingBundle\Entity\ConversationUser $user
     */
    public function removeUser(\Duf\MessagingBundle\Entity\ConversationUser $user)
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

    /**
     * Add message
     *
     * @param \Duf\MessagingBundle\Entity\Message $message
     *
     * @return Conversation
     */
    public function addMessage(\Duf\MessagingBundle\Entity\Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \Duf\MessagingBundle\Entity\Message $message
     */
    public function removeMessage(\Duf\MessagingBundle\Entity\Message $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function getLastMessageDate()
    {
        if (null !== $this->messages) {
            foreach ($this->messages as $message) {
                return $message->getCreatedAt();
            }
        }
        return null;
    }

    public function getLastMessageIntro($limit = 70)
    {
        if (null !== $this->messages) {
            foreach ($this->messages as $message) {
                $intro      = strip_tags($message->getContent());

                return substr($intro, 0, $limit);
            }
        }
        return null;
    }
}
