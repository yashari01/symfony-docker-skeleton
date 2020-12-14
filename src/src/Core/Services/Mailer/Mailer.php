<?php


namespace App\Core\Services\Mailer;


use App\Repository\UserRepository;
use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * @var MailerInterface
     */
    private $mailer;

    public const SEND_EMAIL = 'sendMail';

    /**
     * @Required
     */
    public function setDependency(
        MailerInterface $mailer,
        UserRepository $userRepository
    ){
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }
   public function sendMail(): void
   {
       $users = $this->userRepository->findBy(['optin'=> 1]);
       $user  = array_pop($users);
       $email = (new TemplatedEmail())
           ->from(new Address('a.msouber@gmail.com','ahmed msouber'))
           ->to(new Address($user->getEmail(),$user->getFirstName()))
           ->subject('Welcome to the Space Bar!')
           ->text("Nice to meet you {$user->getFirstName()}! ❤️")
           ->html("<h1>Nice to meet you {$user->getFirstName()}! ❤️</h1>")
           ->context([
               'user' => $user,
           ]);
       $this->mailer->send($email);
   }
}