<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function index(Request $request): Response
    {
        return new Response();
    }

    public function show()
    {

    }

    /**
     * @Route("/users",methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $jsonData = json_decode($request->getContent());

        $user = new User();

        return new JsonResponse($user);
    }

    public function update()
    {
        
    }

    public function delete()
    {
        
    }
}
