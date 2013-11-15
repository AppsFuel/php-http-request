<?php

class HTTP_CurlGroup extends HTTP_Base {
    private $lastRequest = null;
    private $requests = array();
    private $multiCurl = false;


    function __call($method, $arguments) {
        if (in_array($method, $this->magic_methods)) {
            $this->lastRequest->method($method);
            $this->requests[] = $this->lastRequest;
            $this->lastRequest = null;
            return $this;
        }
        if (is_null($this->lastRequest)) {
            $this->lastRequest = $this->createNewRequest();
        }
        call_user_func_array(array($this->lastRequest, $method), $arguments);
        return $this;
    }

    function getRequests() {
        return $this->requests;
    }    
}