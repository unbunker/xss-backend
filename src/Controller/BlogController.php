<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BlogRepository;
use Symfony\Component\Serializer\SerializerInterface;
use App\Serializer\BlogSerializer;

use App\Entity\Blog;

class BlogController extends AbstractController
{

    private $blogRepository;

    public function __construct(
        EntityManagerInterface $em,
        BlogRepository $blogRepository
    )
    {     
        $this->em = $em;
        $this->blogRepository = $blogRepository;
    }


    /**
     * @Route("/api/blogs", name="get_Blog", methods={"GET"})
     */
    public function getBlogs()
    {
        $blogs =  $this->blogRepository->findAll();
        $serializedBlogs = [];

        foreach ($blogs as $blog) {
            $serializedBlogs[] = [
                'userText' => $blog->getUserText(),
                'url' => $blog->getUrl()
            ];
        }

        return new JsonResponse($serializedBlogs, JsonResponse::HTTP_OK);
    }


    /**
     * @Route("/api/blogs", name="create_Blog", methods={"POST"})
     */
    public function createBlog(Request $request)
    {
        $requestData = $request->toArray();
        $errors = $this->validateCreateRequest($requestData);

        if (count($errors) > 0 ) {
            return new JsonResponse($errors, 400);
        }

        $text = $requestData['text'];
        $url= $requestData['imageUrl'];


        $Blog = new Blog($text, $url);

        $this->em->persist($Blog);
        $this->em->flush();

        return new JsonResponse(["status" => "ok"], JsonResponse::HTTP_CREATED);
    }

/**
     * @param string[] $request
     * @return string[]
     */
    public function validateCreateRequest(array $request): array
    {
        $errors = [];

        if (!isset($request['text']) || $request['text'] == "") {
            $errors["text"] = 'No text was specified';
        } 

        if (!isset($request['imageUrl']) || $request['imageUrl'] == "") {
            $errors["imageUrl"] = 'No imageUrl was specified';
        } 

        return $errors;
    }

}
