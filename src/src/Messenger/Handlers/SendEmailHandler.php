<?php


namespace App\Messenger\Handlers;


use App\Core\Services\Mailer\Mailer;
use App\Messenger\Messages\SendEmail;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SendEmailHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(UserRepository $userRepository,Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(SendEmail $sendEmail)
    {
       $user = $this->userRepository->findOneBy(['id'=>$sendEmail->getUserId()]);
       $this->mailer->sendMail();
    }
}