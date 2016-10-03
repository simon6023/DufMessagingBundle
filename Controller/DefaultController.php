<?php

namespace Duf\MessagingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DufMessagingBundle:Default:index.html.twig');
    }
}
