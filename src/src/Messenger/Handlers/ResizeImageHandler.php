<?php


namespace App\Messenger\Handlers;


use App\Messenger\Messages\ResizeImage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ResizeImageHandler implements MessageHandlerInterface
{
    public function __invoke(ResizeImage $resizeImage)
    {
        // TODO: Implement __invoke() method.
    }
}