<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AutoAuthorCommandProcessor implements ProcessorInterface
{
    public function __construct(private Security $security, private PersistProcessor $persistProcessor)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // Handle the state
        $user = $this->security->getUser();
        $data->setAuthor($user);
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
