<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\DuplicateFieldValueException;
use App\Form\UserType;
use App\Service\User\Mysql\UserCreator;
use App\Service\User\UserCreatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @var UserCreatorInterface
     */
    private $userCreator;

    /**
     * RegisterController constructor.
     * @param UserCreatorInterface $userCreator
     */
    public function __construct(UserCreatorInterface $userCreator)
    {
        $this->userCreator = $userCreator;
    }

    /**
     * @param Request $request

     * @return Response
     *
     * @Route("/register", name="register")
     */
    public function register(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->userCreator->create($form->getData());

                $this->addFlash('Success', 'Successfully registered account!');
            } catch (DuplicateFieldValueException $exception) {
                $this->addFlash('danger', 'Error, username already taken.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Sorry we cannot create you account now.');
            }
        }

        return $this->render(
            'register/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}