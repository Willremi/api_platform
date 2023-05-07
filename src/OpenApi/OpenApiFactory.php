<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decored)
    {
        
    }

    public function __invoke(array $context = []): OpenApi
    {
       $openApi = $this->decored->__invoke($context);
       foreach ($openApi->getPaths()->getPaths() as $key => $path) {
         if($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
            $openApi->getPaths()->addPath($key, $path->withGet(null));
         }
       }
    //    dd($openApi);
       return $openApi;
    }
}