<?php

namespace PrologueFramework\Http\Server\RunTime;

Class RunTime
{

    public static function runMethod($params)
    {

        $request = self::runMethod__getRequest();

        $response = self::runMethod__getRequest__setResponse($request, $response);

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

    private static function runMethod__getRequest()
    {

        $request = file_get_contents('php://input');

        if (!$request) {
            return false;
        }

        return json_decode($request);

    }

    private static function runMethod__getRequest__setResponse($request, $response)
    {
        if ($request->requestType == 'loadPage') {
            $response['metaData'] = [
                'title' => '',
                'description' => '',
                'h1' => '',
            ];
        }

        return $response;
    }

}
