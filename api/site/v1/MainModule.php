<?php

namespace api\site\v1;

Class MainModule
{

    public static function loadApp($request, $response)
    {
        $response["status"] = 'ok';

        return $response; 
    }

    public static function ping($request, $response)
    {
        $response["status"] = 'ok';

        return $response; 
    }

}