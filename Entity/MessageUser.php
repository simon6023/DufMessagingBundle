<?php
namespace Duf\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Model\DufAdminUserInterface;

/**
* @ORM\Entity()
*/
class MessageUser extends DufAdminEntity implements DufAdminUserInterface
{
     /**
     * @ORM\ManyToOne(targetEntity="Duf\AdminBundle\Model\DufAdminUserInterface", inversedBy="phonesNbrs", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @ORM\OrderBy({"id" = "ASC"})
     * @var DufAdminUserInterface
     */
     private $user;

     /**
     * @ORM\ManyToOne(targetEntity="Duf\MessagingBundle\Entity\Message", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
     private $message;

    /**
     * @ORM\Column(name="is_read", type="boolean", nullable=true)
     */
    private $isRead;

    /**
     * Set user
     *
     * @param \Duf\AdminBundle\Model\DufAdminUserInterface $user
     *
     * @return MessageUser
     */
    public function setUser(\Duf\AdminBundle\Model\DufAdminUserInterface $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Duf\AdminBundle\Model\DufAdminUserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set message
     *
     * @param \Duf\MessagingBundle\Entity\Message $message
     *
     * @return MessageUser
     */
    public function setMessage(\Duf\MessagingBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \Duf\MessagingBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return MessageUser
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
}
