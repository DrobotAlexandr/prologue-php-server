<?php

namespace PrologueFramework\Http\Server\EndpointWorkSpace;

Class EndpointWorkSpace
{
    public static function buildWorkSpace($params)
    {
        if (!self::buildWorkSpace__isDevMode()) {
            return false;
        }

        if (!$params['endpointWorkSpace']) {
            return false;
        }

        foreach ($params['endpointWorkSpace'] as $workSpace) {
            self::handleWorkSpace($workSpace);
        }

    }

    private static function buildWorkSpace__isDevMode()
    {

        if (strstr($_SERVER['SERVER_NAME'], '.loc')) {
            return true;
        }

        if ($_SERVER['SERVER_NAME'] === 'prologue-php-server') {
            return true;
        }

        return false;
    }

    private static function handleWorkSpace($workSpace)
    {

        $endpoints = self::handleWorkSpace__getEndpoints(
            self::handleWorkSpace__getJsonFiles($workSpace['jsonSourceFolder'], $workSpace['endpointWorkSpace']),
            $workSpace['endpointWorkSpace']
        );

        self::handleWorkSpace__createEndpointsClasses($endpoints);

        self::handleWorkSpace__createEndpointsMethods($endpoints);

    }

    private static function handleWorkSpace__createEndpointsMethods($endpoints)
    {
        if (!$endpoints) {
            return false;
        }


        foreach ($endpoints as $endpoint) {

            if (file_exists($endpoint['fileClassPath'])) {

                $class = file_get_contents($endpoint['fileClassPath']) . '<<<<<<<<<<';

                $methodSearch = 'public static function ' . $endpoint['methodName'];

                if (strstr($class, $methodSearch)) {
                    return false;
                }

                $code = '';

                foreach (array_keys($endpoint['response']) as $key) {

                    $code .= '$response["' . $key . '"] = ' . $endpoint['response'][$key] . ';' . PHP_EOL;

                }


                $code .= PHP_EOL . '        return $response;';


                $method = '    public static function ' . $endpoint['methodName'] . '($request, $response)' . PHP_EOL . '    {' . PHP_EOL . '        ' . $code . ' ' . PHP_EOL . '    }';

                $class = strtr($class,
                    [
                        '}<<<<<<<<<<' => $method . PHP_EOL . PHP_EOL . '}',
                    ]
                );

                file_put_contents($endpoint['fileClassPath'], $class);

            }

        }

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
                        '#className#' => (string)explode('/', $endpoint['className'])[substr_count($endpoint['className'], '/')],
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

        $arData = [];

        foreach (array_keys($data) as $key) {

            ob_start();
            var_export($data[$key]);
            $data[$key] = ob_get_contents();
            ob_end_clean();

            if ($key == 'app') {
                continue;
            }

            if ($key == 'metaData') {
                continue;
            }

            if ($key == 'state') {
                continue;
            }

            if ($key == 'access') {
                continue;
            }


            $arData[$key] = $data[$key];
        }

        return $arData;

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
