<?php

namespace PrologueFramework\Http\Server\EndpointWorkSpace;

Class EndpointWorkSpace
{
    public static function buildWorkSpace($params)
    {

        if (!$params['endpointWorkSpace']) {
            return false;
        }

        foreach ($params['endpointWorkSpace'] as $workSpace) {
            self::handleWorkSpace($workSpace);
        }

    }

    private static function handleWorkSpace($workSpace)
    {

        $endpoints = self::handleWorkSpace__getEndpoints(
            self::handleWorkSpace__getJsonFiles($workSpace['jsonSourceFolder'], $workSpace['endpointWorkSpace']),
            $workSpace['endpointWorkSpace']
        );

        self::handleWorkSpace__createEndpointsClasses($endpoints);

        echo '<pre>';
        print_r($endpoints);
        exit();
    }

    private static function handleWorkSpace__createEndpointsClasses($endpoints)
    {
        if (!$endpoints) {
            return false;
        }

        foreach ($endpoints as $endpoint) {

            if (!file_exists($endpoint['fileClassPath'])) {
                self::handleWorkSpace__createEndpointsClasses__createFolders($endpoint['fileClassPath']);

                $content = file_get_contents(__DIR__ . '/Signatures/Class.php');

                $content = strtr($content,
                    [
                        '#nameSpace#' => strtr(rtrim($endpoint['classNameSpace'], '/'), ['/' => '\\']) . ';',
                        '#className#' => $endpoint['className'],
                    ]
                );

                file_put_contents($endpoint['fileClassPath'], $content);
            }

        }

    }

    private static function handleWorkSpace__createEndpointsClasses__createFolders($fileClassPath)
    {
        $fileClassPath = explode('/', $fileClassPath);

        $pathString = '';

        foreach ($fileClassPath as $path) {

            $pathString .= $path . '/';

            if (strstr($pathString, $_SERVER['DOCUMENT_ROOT'])) {

                if (!file_exists($pathString)) {
                    if (!strstr($pathString, '.php')) {
                        mkdir($pathString);
                    }
                }

            }

        }

    }

    private static function handleWorkSpace__getEndpoints($endpoints, $endpointWorkSpace)
    {

        $arData = [];

        if ($endpoints) {
            foreach ($endpoints as $endpoint) {

                $fileName = self::handleWorkSpace__getEndpoints__getFileName($endpoint);

                $methodName = strtr($fileName, ['.json' => '']);

                $fileClassPath = strtr($endpoint['path'], ['/' . $methodName . '.json' => '']) . '.php';

                $className = ltrim(
                    strtr($fileClassPath,
                        [
                            $_SERVER['DOCUMENT_ROOT'] => '',
                            $endpointWorkSpace => '',
                        ]
                    ),
                    '/'
                );


                $classNameSpace = ltrim(
                    strtr($fileClassPath,
                        [
                            $_SERVER['DOCUMENT_ROOT'] => '',
                            $className => ''
                        ]
                    ),
                    '/'
                );

                $className = strtr($className, ['.php' => '']);


                $arData[] = [
                    'fileClassPath' => $fileClassPath,
                    'classNameSpace' => $classNameSpace,
                    'className' => $className,
                    'methodName' => $methodName,
                    'response' => self::handleWorkSpace__getEndpoints__getResponse($endpoint['json'])
                ];

            }
        }

        return $arData;

    }

    private static function handleWorkSpace__getEndpoints__getResponse($json)
    {
        if (!$json) {
            return false;
        }

        $data = json_decode($json);

        if (!$data) {
            return false;
        }

        $data = self::objectToArray($data);

        ob_start();
        var_export($data);
        $data = ob_get_contents() . '---';
        ob_end_clean();

        $data = strtr($data, ['array (' => '[', ')---' => ']']);

        return $data;

    }

    private static function objectToArray($object)
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }

        return array_map('self::objectToArray', (array)$object);
    }

    private function handleWorkSpace__getEndpoints__getFileName($endpoint)
    {
        $arPath = explode('/', $endpoint['path']);

        foreach ($arPath as $part) {
            if (strstr($part, '.json')) {
                return $part;
            }
        }

        return false;

    }

    private
    static function handleWorkSpace__getJsonFiles($jsonSourceFolder, $endpointWorkSpace)
    {
        $jsonFiles = self::getDirContents($_SERVER['DOCUMENT_ROOT'] . $jsonSourceFolder);

        if (!$jsonFiles) {
            return false;
        }

        $arData = [];

        foreach ($jsonFiles as $file) {
            if (strstr($file, '.json')) {

                $file = strtr($file, ['\\' => '/']);

                $data['json'] = file_get_contents($file);

                $data['path'] = strtr($file, [$jsonSourceFolder => $endpointWorkSpace]);

                $arData[] = $data;
            }
        }

        return $arData;

    }

    private
    static function getDirContents($dir, &$results = [])
    {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                self::getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }

}