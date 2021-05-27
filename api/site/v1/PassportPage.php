<?php

namespace api\site\v1;

Class PassportPage
{

    public static function getSocials($request, $response)
    {
        $response["status"] = 'ok';
$response["socials"] = array (
  0 => 
  array (
    'id' => 'social-1',
    'link' => '/',
    'ico' => '/img/content/google.svg',
  ),
  1 => 
  array (
    'id' => 'social-2',
    'link' => '/',
    'ico' => '/img/content/facebook.svg',
  ),
  2 => 
  array (
    'id' => 'social-3',
    'link' => '/',
    'ico' => '/img/content/ok.svg',
  ),
  3 => 
  array (
    'id' => 'social-4',
    'link' => '/',
    'ico' => '/img/content/vk.svg',
  ),
  4 => 
  array (
    'id' => 'social-5',
    'link' => '/',
    'ico' => '/img/content/ya.svg',
  ),
);

        return $response; 
    }

    public static function resetPassword($request, $response)
    {
        $response["status"] = 'ok';

        return $response; 
    }

    public static function setAuthInfo($request, $response)
    {
        $response["status"] = 'ok';

        return $response; 
    }

    public static function setRegInfo($request, $response)
    {
        $response["status"] = 'ok';

        return $response; 
    }

}