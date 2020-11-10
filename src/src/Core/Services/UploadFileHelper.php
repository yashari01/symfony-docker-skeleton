<?php


namespace App\Core\Services;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFileHelper
{


    const FOLDER_IMAGE = 'user_image';
    /**
     * @var RequestStackContext
     */
    private $requestStackContext;
    /**
     * @var FilesystemInterface
     */
    private $publicUploadsFilesystem;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $publicAssetBaseUrl;

    public function __construct(
        FilesystemInterface $publicUploadsFilesystem,
        RequestStackContext $requestStackContext,
        LoggerInterface $logger,
        string $uploadedAssetsBaseUrl
    )
    {
        $this->requestStackContext = $requestStackContext;
        $this->publicUploadsFilesystem = $publicUploadsFilesystem;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
    }

    public function upload(File $file,?string $existingFilename): ?string
    {
        if($file){

            $fileNameWithoutExtention = Urlizer::urlize(
                pathinfo(
                    $file instanceof UploadedFile ?
                        $file->getClientOriginalName():$file->getFilename()
                    ,PATHINFO_FILENAME
                )
            );
            $uniqFileName = $fileNameWithoutExtention.'-'.uniqid().'.'.$file->guessExtension();
            $stream = fopen($file->getPathname(), 'r');
            $result = $this->publicUploadsFilesystem->writeStream(
                self::FOLDER_IMAGE.'/'.$uniqFileName,
                $stream
            );
            if ($result === false) {
                throw new \Exception(sprintf('Could not write uploaded file "%s"', $uniqFileName));
            }
            if (is_resource($stream)) {
                fclose($stream);
            }
            if ($existingFilename) {
                try {
                    $this->publicUploadsFilesystem->delete(self::FOLDER_IMAGE.'/'.$existingFilename);
                }catch (FileNotFoundException $e){
                    $this->logger->alert(sprintf(
                        'Old uploaded file "%s" was missing when trying to delete', $existingFilename
                    ));
                }

            }
            //$uploadedFile->move($this->uploadPath,$uniqFileName);
            return $uniqFileName;
        }
        return null;
    }
    public function getPublicPath(string $path): string
    {
        $fullPath = $this->publicAssetBaseUrl.'/'.$path;
        // if it's already absolute, just return
        if (strpos($fullPath, '://') !== false) {
            return $fullPath;
        }
        // needed if you deploy under a subdirectory
        return $this->requestStackContext
                ->getBasePath().$fullPath;

       /* return $this->requestStackContext
                ->getBasePath().$this->publicAssetBaseUrl.'/'.$path;*/
    }
}