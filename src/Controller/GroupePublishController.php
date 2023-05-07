<?php

namespace App\Controller;

use App\Entity\Groupe;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GroupePublishController
{
    public function __invoke(Groupe $data)
    {
        $data->setOnline(true);
        return $data;
    }
}