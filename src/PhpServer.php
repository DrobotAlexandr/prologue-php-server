<?php

namespace PrologueFramework\Http\Server;

use  PrologueFramework\Http\Server\EndpointWorkSpace\EndpointWorkSpace;
use  PrologueFramework\Http\Server\RunTime\RunTime;

Class PhpServer
{

    private $endpoint = '';

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

        $this->getEndpoint();

        $this->includeEndpoint();

        $this->runMethod();

        echo 'Server run!';
    }


    private function getEndpoint()
    {
        $this->endpoint = EndpointWorkSpace::getEndpoint();

    }

    private function includeEndpoint()
    {
        include $this->endpoint['classPath'];
    }

    private function runMethod()
    {

        $result = RunTime::runMethod(
            [
                'className' => $this->endpoint['className'],
                'method' => $this->endpoint['method'],
            ]
        );

        echo '<pre>';
        print_r($result);
        exit();
    }

}
