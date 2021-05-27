<?php

namespace PrologueFramework\Http\Server\RunTime;

Class RunTime
{

    public static function runMethod($params)
    {

        $result = call_user_func_array(
            [
                $params['className'],
                $params['method']
            ],
            [
                $request,
                $response
            ]
        );

        return $result;
    }

}