<?php

namespace AppBundle\Controller\UserManagement\Student;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class RegisterController extends Controller
{
    /**
     * @Route("/register/student", name="register_student")
     */
    public function registerAction()
    {
        return $this->container
            ->get('pugx_multi_user.registration_manager')
            ->register('AppBundle\Entity\Student');
    }
}