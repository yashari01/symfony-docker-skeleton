<?php


namespace App\Controller;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FolderController extends AbstractController
{

    /**
     * @Route("/user/{id}/references",name="reference_upload",methods="{POST}")
     * @IsGranted("MANAGE",subject="user")
     */
    public function uploadReference(User $user, Request $request,ValidatorInterface $validator){

        $uploadedFile = $request->files()->get('reference');
        $violations = $validator->validate(
            $uploadedFile,
            new NotBlank(),
            [new File([
                'maxSize' => '1k',
                'mimeTypes' => [
                    'image/*',
                    'application/pdf',
                    'application/msword',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'text/plain'
                    ]
                ])
            ]
        );
        if ($violations->count() > 0) {
            $violation = $violations[0];
            $this->addFlash('error', $violation->getMessage());
        }
        return $this->redirectToRoute('user_show', [
            'id' => $user->getId(),
        ]);
    }
}