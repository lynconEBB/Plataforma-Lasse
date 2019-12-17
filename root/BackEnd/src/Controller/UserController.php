<?php


namespace App\Controller;


use App\Entity\User;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function index()
    {
        return new Response();
    }

    /**
     * @Route("/users",methods={"GET"})
     */
    public function show()
    {

    }

    /**
     * @Route("/users",methods={"POST"})
     */
    public function store()
    {
        return new JsonResponse();
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }


}
