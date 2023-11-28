<?php

namespace SysCore\Utils;

use SysCore\Main;

class API {

  private static $baseUrl = "http://localhost:8080";

  public static function get($url, $body = []) {
    $context  = stream_context_create([
      'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'GET',
          'content' => http_build_query($body)
      ]
    ]);
    
    // Eksekusi POST request dan dapatkan respons
    $response = file_get_contents(self::$baseUrl . $url, false, $context);

    return json_decode($response, true);
  }

  public static function post($url, $body = []) {
    $context  = stream_context_create([
      'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($body)
      ]
    ]);
    
    // Eksekusi POST request dan dapatkan respons
    $response = file_get_contents(self::$baseUrl . $url, false, $context);

    return json_decode($response, true);
  }
  
  public static function logResponse($res) {
      if (isset($res['status'])) {
          if ($res['status'] !== 200) {
              Main::getInstance()->getLogger()->alert($res['message']);
          }
      }else {
          var_dump($res);
      }
  }
}