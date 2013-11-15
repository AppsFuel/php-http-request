<?php

abstract class HTTP_Base {
    protected $magic_methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD');
    static protected $buildBodyHandle = null;
    
    protected function createNewRequest() {
        return new HTTP_Request;
    }

    static function setBuildBodyHandle($handle) {
        $this->buildBodyHandle = $handle;
    }

    protected function buildCurl($request) {
        $url = $request->url;
        $par = http_build_query($request->param);
        if (!empty($par)) {
            $url .= '?' . $par;
        }

        $method = $request->method;
        $body = $request->body;
        $headers = $request->headers;


        if (isset($headers['Content-type'])) {
            $body = $this->buildBody($body, $headers['Content-type']);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        if (!is_null($body)) {
            // curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        return $ch;
    }

    protected function buildBody($body, $contentType) {
        if (strpos($contentType, 'json') !== false) {
            return json_encode($body);
        }
        if (!is_null($this->buildBodyHandle)) {
            $body = call_user_func(self::$buildBodyHandle, $body, $contentType);
        }
        throw new Exception('Unknown Content-Type');
    }

    protected function createResponse($ch, $body, $request) {
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($body, 0, $header_size);
        $headers = explode("\r\n\r\n", $header);
        array_pop($headers);
        $body = substr($body, $header_size);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        $response = new HTTP_Response;
        $response->request = $request;
        $response->body = $body;
        $response->status_code = $status_code;
        $response->curl_error = $curl_error;
        $response->headers = $headers;

        return $response;
    }
}