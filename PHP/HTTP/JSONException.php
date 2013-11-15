<?php

class HTTP_JSONException extends Exception {
	private $response = null;

	public $jsonError = null;
	public $jsonErrorMessage = null;

	function setResponse($response) {
		$this->response = $response;
		return $this;
	}

	function getResponse() {
		return $this->response;
	}

	function setupJsonError() {
		$this->jsonError = json_last_error();
		$this->jsonErrorMessage = json_last_error_msg();
		return $this;
	}
}