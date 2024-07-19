<?php
 
namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
 
class JWTCreatedListener
{
    public function __construct(private UserRepository $userRepository)
    {
    }
 
    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        // Récupération du payload
        $payload = $event->getData();
        // Récupération de l'utilisateur concerné
        $user = $this->userRepository->findOneByEmail($payload['username']);
 
        // Ajout d'une clé 'firstname' au payload
        $payload['fullname'] = $user->getFullname();
 
        // Mise à jour du payload
        $event->setData($payload);
    }
}