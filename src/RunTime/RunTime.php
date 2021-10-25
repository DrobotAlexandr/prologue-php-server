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

            $page = false;

            if (class_exists('\App\Models\Pages\Pages')) {
                $page = \App\Models\Pages\Pages::getPageForRouter(
                    [
                        'url' => $request->client->device->http->url
                    ]
                );
            }

            $response['metaData'] = [
                'title' => $page['meta_title'],
                'description' => $page['meta_description'],
                'h1' => $page['meta_heading'],
            ];

            if ($page['meta_heading'] != '404') {
                $response['status'] = 200;
            } else {
                $response['status'] = 404;
            }


        }

        return $response;
    }

}
