<?php


$base = __DIR__ . '/HTTP/';
include $base . 'Exception.php';
include $base . 'JSONException.php';
include $base . 'Base.php';
include $base . 'Opener.php';
include $base . 'CurlGroup.php';
include $base . 'Request.php';
include $base . 'Response.php';


class HTTP {
    static function get($url, $params) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->params($params)
            ->GET();
    }

    static function post($url, $body) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->body($params)
            ->POST();
    }

    static function put($url, $body) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->body($params)
            ->POST();
    }

    static function patch($url, $body) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->body($params)
            ->POST();
    }

    static function delete($url) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->DELETE();
    }

    static function options($url) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->OPTIONS();
    }

    static function heder($url) {
        $http = new HTTP_Opener;
        return $http->url($url)
            ->HEAD();
    }
}