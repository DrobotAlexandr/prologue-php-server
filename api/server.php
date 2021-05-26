<?php

include '../src/EndpointWorkSpace/EndpointWorkSpace.php';

include '../src/PhpServer.php';

$server = new PrologueFramework\Http\Server\PhpServer();

$server->setApiEndpointsWorkSpace(
    [
        [
            'jsonSourceFolder' => '/app/interface/site/v1/api/',
            'endpointWorkSpace' => '/api/site/v1/',
        ]
    ]
);

$server->run();