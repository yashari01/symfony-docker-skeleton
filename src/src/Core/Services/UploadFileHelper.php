<?php


namespace App\Core\Services;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileHelper
{
    /**
     * @var string
     */
    private $uploadPath;

    const FOLDER_IMAGE = 'user_image';
    /**
     * @var RequestStackContext
     */
    private $requestStackContext;

    public function __construct(string $uploadPath,RequestStackContext $requestStackContext)
    {
        $this->uploadPath = $uploadPath;
        $this->requestStackContext = $requestStackContext;
    }

    public function upload(UploadedFile $uploadedFile): ?string
    {
        if($uploadedFile){
            $fileNameWithoutExtention = Urlizer::urlize(
                pathinfo($uploadedFile->getClientOriginalName(),PATHINFO_FILENAME)
            );
            $uniqFileName = $fileNameWithoutExtention.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move($this->uploadPath,$uniqFileName);
            return $uniqFileName;
        }
        return null;
    }
    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext
                ->getBasePath().'/uploads/'.$path;
    }
}