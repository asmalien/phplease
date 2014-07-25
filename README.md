# PHPlease - A PHP library that wraps around the xREL API

Learn about the xREL API at the [documentation](http://www.xrel.to/wiki/1681/API.html).

## Example

    # Load the latest releases
    $response = $xrel->latest();

    # Display the dirname of each release
    foreach($response['list'] as $item) {
        echo($item['dirname']);
    }

Checkout `example` for more.

## Use Composer

composer.json

```
{
	"require": {
		"asmalien/phplease": "dev-master"
	},
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/asmalien/phplease.git"
		}
	]
}
```

```php composer.phar install```

app.php
```
<?php
require_once( __DIR__.'/vendor/autoload.php');

$client = new PHPlease\PHPleaseAuth();

# Create an instance of PHPlease
$xrel = new PHPlease\PHPlease($client);

# Returns the latest releases.
$response = $xrel->latest();
var_dump($response);
```

## Copyright

Copyright (c) 2014 h0kx. See [LICENSE](http://l.h0kx.me/) for details.
