<?php

namespace App\Controller;

use App\Entity\User;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

class PictureController extends AbstractController
{
    #[Route('/avatar', name: 'picture_avatar')]
    public function index(): Response
    {
        /**
         * @var string $pictureDirectory
         */
        $pictureDirectory = $this->getParameter('app.dir.avatar');
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if (!($user !== null && $user->getAvatar() !== null)) {
            $pictureName = 'avatar.png';
        } else {
            $pictureName = (string)$user->getId() . DIRECTORY_SEPARATOR . $user->getAvatar();
        }
        $pictureFile = $pictureDirectory . DIRECTORY_SEPARATOR . $pictureName;
        if (!file_exists($pictureFile)) {
            $pictureName = 'avatar.png';
        }
        $file = fopen($pictureFile, 'r');
        if (!$file) {
            throw new Exception('Could not open avatar');
        }
        $stream = new StreamedResponse(
            function () use ($file) {

                while (!feof($file)) {
                    echo fread($file, 1024);
                }
                fclose($file);
            },
            200,
            [
                'Content-Type' => 'image/png',
                'Content-Length' => filesize($pictureFile),
                'Content-Disposition' => 'inline; filename="avatar.png"',
            ]
        );
        return $stream;
    }
}
