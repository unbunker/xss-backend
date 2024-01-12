<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Psr\Log\LoggerInterface;

#[Route('/auth', name: 'auth_api')]
class AuthController extends AbstractController
{
    private $em;
    private $userRepository;
    private $jwtManager;
    private $validator;
    private $logger;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        LoggerInterface $logger
    )
    {    
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     * @Route("/register", methods={"POST"}, name="create_user")
     * ,
     */
    public function create(SerializerInterface $serializer, Request $request): Response
    {

        $requestData = $request->toArray();

        $errors = $this->validateCreateRequest($requestData);

        if (count($errors) > 0 ) {
            return new JsonResponse($errors, 400);
        }

        $email = $requestData['email'];
        $password = null;

        if (isset($requestData['password'])) {
            $password = $requestData['password'];
        }

        $user = new User($email, $password, $requestData['fullName'], true);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json([
            'status' => 'ok'
        ]);
    }

    /**
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return Response
     * @Route("/login", methods={"POST"}, name="login")
     * ,
     */
    public function login(SerializerInterface $serializer, Request $request): Response
    {

        $requestData = $request->toArray();

        $errors = $this->validateLoginRequest($requestData);


        if (count($errors) > 0 ) {
            return new JsonResponse($errors, 400);
        }
        $email = $requestData['email'];
        $password = $requestData['password'];

        $user = $this->userRepository->findOneBy(['email' => $requestData['email']]);

        if ($user == null ) {
            return new JsonResponse(['error' => 'user was not found'], 404);
        }

        if (!$user->verifyPassword($requestData['password'])) {
            return new JsonResponse(['error' => 'The given password is not correct'], 404);
        }

        if (!$user->getActive()) {
            return new JsonResponse(['error' => 'You have not activated your account yet!'], 400);
        }

        return $this->getTokenUser($user, $this->jwtManager);
    }



    /**
     * @param User $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(User $user, JWTTokenManagerInterface $JWTManager)
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }



    /**
     * @param string[] $request
     * @return string[]
     */
    public function validateCreateRequest(array $request): array
    {
        $errors = [];

        if (!isset($request['email']) || $request['email'] == "") {
            $errors["email"] = 'No email was specified';
        } else {
            $user = $this->userRepository->findOneBy(['email' => $request['email']]);
            if ($user != null) {
                $errors["email"] = 'A user with this email already exists';
            }
        }

        if (count($errors) > 0) {
            return $errors;
        }   

        $data = [
            'email' => $request['email'] ?? "",
            'fullName' => $request['fullName'] ?? "",
            'password' => $request['password'] ?? ""
        ];

        // Define the validation constraints for email and password
        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3, 'max' => 50]),
            ],
            'fullName' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3, 'max' => 60]),
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 6]),
            ],
        ]);

        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            // If there are validation errors, return a JSON response with the errors
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
        }



        return $errors;
    }


    /**
     * @param string[] $request
     * @return string[]
     */
    public function validateLoginRequest(array $request): array
    {
        $errors = [];

        if (!isset($request['email']) || $request['email'] == "") {
            $errors["email"] = 'No email was specified';
        } 

        if (!isset($request['password']) || $request['password'] == "") {
            $errors["password"] = 'No password was specified';
        } 

        if (count($errors) > 0) {
            return $errors;
        }   

        // Define the validation constraints for email and password
        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3, 'max' => 50]),
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 6]),
            ],
        ]);

        $data = [
            'email' => $request['email'],
            'password' => $request['password']
        ];

        $violations = $this->validator->validate($data, $constraints);

        if (count($violations) > 0) {
            // If there are validation errors, return a JSON response with the errors
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
        }


        return $errors;
    }
}
