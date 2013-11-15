<?php

class HTTP_Request {
	public $url = null;
	public $body = null;
	public $param = array();
	public $method = null;
	public $headers = array();

	function url($url) {
		$this->url = $url;
	}

	function body($body) {
		$this->body = $body;
	}

    function param($key, $value) {
        $this->param[$key] = $value;
    }

    function params($params) {
        $this->param = array_merge($this->param, $params);
    }

	function method($method) {
		$this->method = $method;
	}

	function header($key, $value) {
		$this->headers[$key] = $value;
	}

	function setContentType($contentType) {
		$this->header('Content-type', $contentType);
	}
}