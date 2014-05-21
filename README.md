# PHPlease - A PHP library that wraps around the xREL API

Learn about the xREL API at the [documentation](http://www.xrel.to/wiki/1681/API.html).

Questions, comments? b@h0kx.me

## Example

    # Load the latest releases
    $response = $xrel->latest();

    # Display the dirname of each release
    foreach($response['list'] as $item) {
        echo($item['dirname']);
    }

Checkout `example` for more.

## Copyright

Copyright (c) 2014 h0kx. See [LICENSE](http://l.h0kx.me/) for details.
