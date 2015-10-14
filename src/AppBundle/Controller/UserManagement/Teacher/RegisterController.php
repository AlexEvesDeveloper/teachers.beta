<?php

namespace AppBundle\Controller\UserManagement\Teacher;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class RegisterController extends Controller
{
    /**
     * @Route("/register/teacher", name="register_teacher")
     */
    public function registerAction()
    {
        return $this->container
            ->get('pugx_multi_user.registration_manager')
            ->register('AppBundle\Entity\Teacher');
    }
}