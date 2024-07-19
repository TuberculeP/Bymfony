<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatchDrinkController extends AbstractController
{
    public function __invoke(EntityManagerInterface $entityManagerInterface, Request $request)
    {
        
        if ($request->request->get('media')) {
            $requestMedia = trim($request->request->get('media'));
            if ($requestMedia == null || $requestMedia = "") {
                throw new Exception('no media link submitted');
            }
            $mediaId = explode('/', $requestMedia);
            $media = $entityManagerInterface->getRepository(Media::class)->findOneBy(['id' => end($mediaId)]);
            if ($media) {
                $requestDrinkURL = $request->attributes->get('_route');
                dd($requestDrinkURL);
            }
        }
        return;
    }
}
