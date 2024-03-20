<?php

namespace App\Controller;

use Nyholm\Psr7\Response;

class HealthCheckController
{
    public function __invoke(): Response
    {
        return new Response(200, [], json_encode([
            'status' => true,
            'now' => (new \DateTime('now'))->format('Y-m-d H:i:s'),
        ]));
    }
}
