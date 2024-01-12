<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;

class HomeController extends AbstractController
{

    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    #[Route('/', name: 'app_home')]
    public function index(): JsonResponse
    {
        $environment = $this->kernel->getEnvironment();

        return $this->json([
            'status' => 'running',
            'environment' => $environment
        ]);
    }
}
