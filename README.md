php-http-request
================

Modular, Object oriented CURL wrapper


## Usage

```php
$response = $http->url('http://localhost')
    ->body(array('a' => 'aa'))
    ->setContentType('application/json')
    ->POST();
$body = $response->json();
```

You can use the following http methods:
 * GET
 * POST
 * PUT
 * PATCH
 * DELETE
 * OPTIONS
 * HEAD

This package provides the multicurl process
```php
// create 3 requests
$multiOpener = $http->createCurlGroup();

$multiOpener->url('http://localhost')
    ->GET();
$multiOpener->url('http://localhost')
    ->body(array('pippo' => 1))
    ->POST();
$multiOpener->url('http://localhost')
    ->body(array('pippo' => 1))
    ->setContentType('application/json')
    ->PUT();

// execute the requests return an array with all responses
$responses = $http->execute($multiOpener);

foreach ($responses as $response) {
    var_dump($response->json());
}
```

Or you can use the shortcut:
```php
$response = HTTP::get('http://localhost', array('key' => 1));
var_dump($response->body());
```
