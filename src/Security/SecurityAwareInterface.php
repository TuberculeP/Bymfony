<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;

interface SecurityAwareInterface
{
    public function setSecurity(Security $security): void;
}