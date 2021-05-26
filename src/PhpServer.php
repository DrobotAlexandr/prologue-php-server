<?php

namespace PrologueFramework\Http\Server;

use  PrologueFramework\Http\Server\EndpointWorkSpace\EndpointWorkSpace;

Class PhpServer
{

    private $endpointWorkSpace = [
        [
            'jsonSourceFolder' => '/json-api/site/v1/',
            'endpointWorkSpace' => '/api/site/v1/',
        ]
    ];

    public function setApiEndpointsWorkSpace($endpointWorkSpace)
    {
        $this->endpointWorkSpace = $endpointWorkSpace;

    }

    private function buildWorkSpace()
    {
        EndpointWorkSpace::buildWorkSpace(
            [
                'endpointWorkSpace' => $this->endpointWorkSpace
            ]
        );
    }

    public function run()
    {

        $this->buildWorkSpace();

        echo 'Server run!';
    }

}