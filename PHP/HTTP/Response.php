<?php


class HTTP_Response {
	public $response = null;
	public $headers = null;
	public $body = null;
	public $status_code = null;
	public $curl_error = null;

	private $parsedHeaders = null;
	private $cookies = null;

	public function json() {
		$body = json_decode($this->body, true);
		if (json_last_error()) {
			$e = new HTTP_JSONException('Cannot decode json');
			$e->setResponse($this)
				->setupJsonError();
			throw $e;
		}
		return $body;
	}

	public function cookies() {
		if (is_null($this->cookies)) {
			$headers = $this->getParsedHeaders();
			$lastHeaders = array_pop($headers);

			$this->cookies = array();
			foreach ($lastHeaders as $header) {
				if (stripos($header, 'set-cookie') !== FALSE) {
					$tokens = explode(';', array_pop(explode(': ', $header, 2)));;
					list($key, $value) = explode('=', array_shift($tokens));
					$this->cookies[$key] = array(
						'key' => $key,
						'value' => $value,
						'other' => $tokens,
					);
				}
			}
		}
		return $this->cookies;
	}

	public function getParsedHeaders() {
		if (is_null($this->parsedHeaders)) {
			$this->parsedHeaders = array();
			foreach ($this->headers as $headerForRedirect) {
				$this->parsedHeaders[] = explode("\r\n", $headerForRedirect);
			};
		}
		return $this->parsedHeaders;
	}

	public function assertStatusCode($permitted=null) {
		if ($this->curl_error) {
			$e = new HTTP_Exception('Curl fails');
			$e->setResponse($this);
			throw $e;
		}
		$permitted = is_null($permitted) ? array(200, 201, 202, 204) : $permitted;
		if (!in_array($this->status_code, $permitted)) {
			$e = new HTTP_Exception('Wrong status code');
			$e->setResponse($this);
			throw $e;
		}
	}
}