<?php
namespace Duf\MessagingBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormFactory as FormFactory;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager as EntityManager;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Duf\MessagingBundle\Form\MessageType;
use Duf\MessagingBundle\Entity\Conversation;
use Duf\MessagingBundle\Entity\ConversationUser;
use Duf\MessagingBundle\Entity\Message;
use Duf\MessagingBundle\Entity\MessageUser;
use Duf\MessagingBundle\Entity\Draft;
use Duf\MessagingBundle\Entity\DraftUser;

class DufMessaging
{
    private $container;
    private $formFactory;
    private $router;
    private $em;
    private $conversation;

    public function __construct(Container $container, FormFactory $formFactory, Router $router, EntityManager $em)
    {
        $this->container    = $container;
        $this->formFactory  = $formFactory;
        $this->router       = $router;
        $this->em           = $em;

        $this->conversation = null;
    }

    public function setConversation($conversation)
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getCreateForm(Message $entity = null, $users = null)
    {
        if (null === $entity) {
            $entity = new Message();
        }

        $form = $this->formFactory->create(MessageType::class, $entity, array(
            'action'        => $this->router->generate('duf_admin_messaging_create'),
            'method'        => 'POST',
            'users'         => $users,
        ));

        return $form;
    }

    public function getNewConversation($form_data)
    {
        $subject = (isset($form_data['subject'])) ? $form_data['subject'] : null;

        $conversation = new Conversation();
        $conversation->setSubject($subject);

        $this->em->persist($conversation);
        $this->em->flush();

        return $conversation;
    }

    public function getConversations($user, $options = null)
    {
        return $this->em->getRepository('DufMessagingBundle:Conversation')->findByConversationsForUser($user, $options);
    }

    public function createMessage($message, $conversation, $form_data, $author)
    {
        $message->setAuthor($author);
        $message->setConversation($conversation);

        if (isset($form_data['content'])) {
            $message->setContent($form_data['content']);
        }

        $this->em->persist($message);
        $this->em->flush();

        $conversation->addMessage($message);

        // add users to conversation
        $this->addUserToConversation($author, $conversation);

        // get user entity class
        $user_entity_name    = $this->container->get('duf_admin.dufadminconfig')->getDufAdminConfig('user_entity');

        if (isset($form_data['users'])) {
            foreach ($form_data['users'] as $user_id) {
                if ($user_id !== $author->getId()) {
                    $user_entity    = $this->em->getReference($user_entity_name, $user_id);
                    $conv_user      = $this->addUserToConversation($user_entity, $conversation);

                    $this->em->persist($conversation);

                    $msg_user = new MessageUser();
                    $msg_user->setUser($user_entity);
                    $msg_user->setMessage($message);
                    $msg_user->setIsRead(false);

                    $message->addUser($msg_user);
                }
            }
        }

        $this->em->persist($message);
        $this->em->persist($conversation);
        $this->em->flush();

        return array(
                'conversation'  => $conversation,
                'message'       => $message,
            );
    }

    public function isUserInConversation($conversation, $user) {
        // check if user is part of conversation        
        foreach ($conversation->getUsers() as $conversation_user) {
            if ($conversation_user->getUser() == $user) {
                return true;
            }
        }

        return false;
    }

    public function deleteConversation($conversation, $user)
    {
        // get conversationUser entity
        foreach ($conversation->getUsers() as $conversation_user) {
            if ($conversation_user->getUser() == $user) {
                $conversation_user->setIsDeleted(true);

                $this->em->persist($conversation_user);
                $this->em->flush();

                return true;
            }
        }

        return false;
    }

    public function setReadConversation($user, $conversation = null)
    {
        $unread_messages = $this->em->getRepository('DufMessagingBundle:Message')->findByUnreadMessages($user, $conversation);

        foreach ($unread_messages as $message) {
            foreach ($message->getUsers() as $message_user) {
                if ($message_user->getUser() == $user) {
                    $message_user->setIsRead(true);
                    $this->em->persist($message_user);
                }
            }
        }

        $this->em->flush();

        return $this;
    }

    public function getNumberOfUnreadConversations($user)
    {
        return $this->em->getRepository('DufMessagingBundle:Message')->findByUnreadMessages($user, null, array('count' => true, 'isRead' => false));
    }

    public function getNumberOfDrafts($user)
    {
        return $this->em->getRepository('DufMessagingBundle:Draft')->findByUserDrafts($user);
    }

    public function getLastUnreadConversations($user, $limit = 3)
    {
        $messages               = $this->em->getRepository('DufMessagingBundle:Message')->findByUnreadMessages($user, null, array('limit' => $limit, 'isRead' => false));
        $conversations          = array();
        $added_conversations    = array();

        foreach ($messages as $message) {
            if (!in_array($message->getConversation()->getId(), $added_conversations)) {
                $conversations[] = $message->getConversation();
            }
        }

        return $conversations;
    }

    public function isUnreadConversation($conversation, $user)
    {
        foreach ($conversation->getMessages() as $message) {
            foreach ($message->getUsers() as $message_user) {
                if (false == $message_user->getIsRead() && $message_user->getUser() == $user) {
                    return true;
                }
            }
        }

        return false;
    }

    public function createDraft($request, $author)
    {
        $form_data  = $request->get('message');
        $content    = $request->get('content_text');
        $draft_id   = $request->get('draft_id');

        if (null !== $draft_id && $draft_id !== '0' && $draft_id !== 0) {
            $draft      = $this->em->getRepository('DufMessagingBundle:Draft')->findOneById($draft_id);
        }
        else {
            $draft      = new Draft();
        }

        foreach ($draft->getUsers() as $draft_user) {
            $draft->removeUser($draft_user);
        }

        $draft->setSubject($form_data['subject']);
        $draft->setContent($content);
        $draft->setAuthor($author);

        if (isset($form_data['users'])) {
            foreach ($form_data['users'] as $user_id) {
                $user           = $this->em->getRepository('DufAdminBundle:User')->findOneById($user_id);
                $draft_user     = new DraftUser();
                $draft_user->setDraft($draft);
                $draft_user->setUser($user);

                $this->em->persist($draft_user);

                $draft->addUser($draft_user);
            }
        }

        $this->em->persist($draft);
        $this->em->flush();

        return $draft;
    }

    private function addUserToConversation($user, $conversation)
    {
        $add_user = true;

        // check if user is already in conversation
        foreach ($conversation->getUsers() as $conversation_user) {
            if ($conversation_user->getUser() == $user) {
                $add_user = false;
            }
        }

        if (!$add_user) {
            return null;
        }

        $conv_user = new ConversationUser();
        $conv_user->setUser($user);
        $conv_user->setConversation($conversation);
        $conversation->addUser($conv_user);

        return $conv_user;
    }
}