<?php


namespace App\Controller;


use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    public function __construct()
    {

    }

    /**
     * @Route("/api/login",methods={POST})
     */
    public function login(Request $request): Response
    {
        $JSONdata = json_decode($request->getContent());
        if (!property_exists($JSONdata,'login') || !property_exists($JSONdata,'password')) {
            throw new InvalidArgumentException("Login request requires the login and password parameters in json format");
        }


        return new JsonResponse();
    }
}
