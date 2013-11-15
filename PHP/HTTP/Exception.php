<?php

class HTTP_Exception extends Exception {
	private $response = null;

	function setResponse($response) {
		$this->response = $response;
	}

	function getResponse() {
		return $this->response;
	}
}