<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GroupeCountController
{

    public function __construct(private GroupeRepository $groupeRepository)
    {
        
    }

    public function __invoke(Request $request): int
    {
        $onlineQuery = $request->get('online');
        $conditions = [];
        if($onlineQuery !== null) {
            $conditions = ['online' => $onlineQuery === '1' ? true : false];
        }
        return $this->groupeRepository->count($conditions);
    }
}