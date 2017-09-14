## HTTPClient.php
simple lib with class HTTPClient for sending requests using cURL
### Usage
```php
require "HTTPClient.php";
$result = HTTPClient::request('https://github.com');
```

## fddoser
Send requests to some url in parallel processes

### Usage
```bash
php fddoser.php <forks> <repeats> <url>
```
Create 25 processes and make 1000 attempts to send GET-request to URL

----
<a href="https://github.com/effus/php-tools/"><img src="https://img.shields.io/github/license/effus/php-tools.svg"></a>
![compatible](https://img.shields.io/badge/PHP7-Compatible-brightgreen.svg)
