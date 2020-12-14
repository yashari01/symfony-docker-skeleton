<?php

namespace App\Controller;

use App\Core\AppCacheManager;
use App\Core\Services\Mailer\Mailer;
use App\Core\Services\UploadFileHelper;
use App\Entity\User;
use App\Form\UserType;
use App\Messenger\Messages\SendEmail;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, AppCacheManager $cacheManager): Response
    {
        $users = $cacheManager->getUsersCache()->get('users-list', function() use ($userRepository){
            return $userRepository->findAll();
        });
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request,UploadFileHelper $uploadFileHelper): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->add('password',PasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $uploadedFile = $form['fileName']->getData();
            $usreImage = $uploadFileHelper->upload($uploadedFile,$user->getImage());
            $user->setImage($usreImage);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(
        Request $request, 
        User $user,UploadFileHelper $uploadFileHelper,
        MessageBusInterface $messageBus
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['fileName']->getData();
            $usreImage = $uploadFileHelper->upload($uploadedFile,$user->getImage());
            $sendEmailObject = new SendEmail($user->getId(), Mailer::SEND_EMAIL);
            $messageBus->dispatch($sendEmailObject);
            $user->setImage($usreImage);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }




}
