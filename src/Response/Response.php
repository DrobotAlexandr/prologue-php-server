<?php

namespace PrologueFramework\Http\Server\Response;

Class Response
{

    public static function output($params)
    {
        $params['data']['version'] = self::output__geVersion($params);

        $response = self::output__json($params['data']);

        return $response;

    }

    private static function output__geVersion($params)
    {
        return md5(serialize($params));
    }

    private static function output__json($data)
    {
        header('Content-type: application/json;');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        print $json;
        exit();
    }

}