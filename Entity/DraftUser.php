<?php
namespace Duf\MessagingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Duf\AdminBundle\Entity\DufAdminEntity;
use Duf\AdminBundle\Model\DufAdminUserInterface;

/**
* @ORM\Entity()
*/
class DraftUser extends DufAdminEntity implements DufAdminUserInterface
{
     /**
     * @ORM\ManyToOne(targetEntity="Duf\AdminBundle\Model\DufAdminUserInterface", inversedBy="phonesNbrs", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * @ORM\OrderBy({"id" = "ASC"})
     * @var DufAdminUserInterface
     */
     private $user;

     /**
     * @ORM\ManyToOne(targetEntity="Duf\MessagingBundle\Entity\Draft", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(name="draft_id", referencedColumnName="id", nullable=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
     private $draft;

    /**
     * Set user
     *
     * @param \Duf\AdminBundle\Model\DufAdminUserInterface $user
     *
     * @return DraftUser
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
     * Set draft
     *
     * @param \Duf\MessagingBundle\Entity\Draft $draft
     *
     * @return DraftUser
     */
    public function setDraft(\Duf\MessagingBundle\Entity\Draft $draft = null)
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * Get draft
     *
     * @return \Duf\MessagingBundle\Entity\Draft
     */
    public function getDraft()
    {
        return $this->draft;
    }
}
