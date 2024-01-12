<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\ActivateRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;


class TokenController extends AbstractController
{

    private $em;
    private $userRepository;   

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em
    )
    {  
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
    * @Route("/api/refresh", methods={"GET"}, name="get_token")
     */
    public function refresh(JWTTokenManagerInterface $JWTManager)
    {
        $jwtUser = $this->getUser();

        $user = $this->userRepository->findOneBy(['email' => $jwtUser->getEmail()]);

        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }
}
