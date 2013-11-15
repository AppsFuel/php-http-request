<?php

class HTTP_Opener extends HTTP_Base {
    private $request = null;

    function doRequest() {
        $ch = $this->buildCurl($this->request);

        $body = curl_exec($ch);
        $response = $this->createResponse($ch, $body, $this->request);
        curl_close($ch);

        $this->request = null;
        return $response;
    }

    function __call($method, $arguments) {
        if (in_array($method, $this->magic_methods)) {
            $this->request->method($method);
            return $this->doRequest();
        }
        if (is_null($this->request)) {
            $this->request = $this->createNewRequest();
        }
        call_user_func_array(array($this->request, $method), $arguments);
        return $this;
    }

    function createCurlGroup() {
        return new HTTP_CurlGroup;
    }

    /**
     * Execute multi curl
     */
    function execute(HTTP_CurlGroup $multi) {
        $mh = curl_multi_init();

        $requests = $multi->getRequests();

        $curlHandlers = array();
        foreach ($requests as $request) {
            $ch = $this->buildCurl($request);
            $curlHandlers[] = $ch;
            curl_multi_add_handle($mh, $ch);
        }

        $active = null;
        do {
            curl_multi_exec($mh, $active);
        } while($active > 0);

        $responses = array();
        foreach ($requests as $n => $request) {
            $body =  curl_multi_getcontent($curlHandlers[$n]);
            $responses[] = $this->createResponse($curlHandlers[$n], $body, $request);
            curl_multi_remove_handle($mh, $curlHandlers[$n]);
        }
        curl_multi_close($mh);

        $this->multiCurl = false;
        $this->request = null;
        $this->requests = array();

        return $responses;
    }
}
