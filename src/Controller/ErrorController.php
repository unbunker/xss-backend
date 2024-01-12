<?php

namespace App\Controller;


use App\Responses\NotFoundResponse;
use App\Responses\ForbiddenResponse;
use App\Responses\BadRequestResponse;
use App\Responses\InternalServerErrorResponse;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\ContactForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Dotenv\Dotenv;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class ErrorController extends AbstractController
{

    public function show($exception, LoggerInterface $logger): Response
    {
        // $dotenv = new Dotenv();
        // $dotenv->load(dirname(__DIR__, 2).'/.env');
        // $showError = $_ENV['SHOW_ERROR'];

        if ($exception->getStatusCode() == "404"){
            $logger->error('Not found');
            $logger->error($exception->getMessage());
            return new NotFoundResponse("This page could not be found");
        } 
        if ($exception->getStatusCode() == "403"){
            return new ForbiddenResponse(["message" => "Unauthorized"]);
        }
        if ($exception->getStatusCode() == "400"){

            $logger->error('Bad request');
            $logger->error($exception->getMessage());
            return new BadRequestResponse(["message" => $exception->getMessage()]);
        }

        if ($exception->getStatusCode() == "405"){
            return new Response("Method not allowed", 405);
        }
        else {
            $logger->error($exception->getMessage()); 

            return new InternalServerErrorResponse(["message" => "Internal server error"]);
        }
    }
}
