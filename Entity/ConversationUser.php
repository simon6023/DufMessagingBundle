<?php
namespace Duf\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Model\DufAdminUserInterface;

/**
* @ORM\Entity()
*/
class ConversationUser extends DufAdminEntity implements DufAdminUserInterface
{
     /**
     * @ORM\ManyToOne(targetEntity="Duf\AdminBundle\Model\DufAdminUserInterface", inversedBy="phonesNbrs", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @ORM\OrderBy({"id" = "ASC"})
     * @var DufAdminUserInterface
     */
     private $user;

     /**
     * @ORM\ManyToOne(targetEntity="Duf\MessagingBundle\Entity\Conversation", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="conversation_id", referencedColumnName="id", nullable=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
     private $conversation;

    /**
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    private $isDeleted;

    /**
     * Set user
     *
     * @param \Duf\AdminBundle\Model\DufAdminUserInterface $user
     *
     * @return ConversationUser
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
     * Set conversation
     *
     * @param \Duf\MessagingBundle\Entity\Conversation $conversation
     *
     * @return ConversationUser
     */
    public function setConversation(\Duf\MessagingBundle\Entity\Conversation $conversation = null)
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
     * Set isDeleted
     *
     * @param boolean $isDeleted
     *
     * @return ConversationUser
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }
}
