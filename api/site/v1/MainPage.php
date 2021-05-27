<?php

namespace api\site\v1;

Class MainPage
{

    public static function getPage($request, $response)
    {
        $response["status"] = 'ok';

        return $response; 
    }

}