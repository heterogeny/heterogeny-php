# Heterogeny [![Build Status](https://img.shields.io/travis/heterogeny/heterogeny-php.svg)](https://travis-ci.org/heterogeny/heterogeny-php) [![Latest Stable Version](https://img.shields.io/packagist/v/heterogeny/heterogeny-php.svg)](https://packagist.org/packages/heterogeny/heterogeny-php) [![Total Downloads](https://img.shields.io/packagist/dt/heterogeny/heterogeny-php.svg)](https://packagist.org/packages/heterogeny/heterogeny-php) [![Latest Unstable Version](https://img.shields.io/packagist/vpre/heterogeny/heterogeny-php.svg)](https://packagist.org/packages/heterogeny/heterogeny-php) [![License](https://img.shields.io/github/license/heterogeny/heterogeny-php.svg)](https://github.com/heterogeny/heterogeny-php/blob/master/LICENSE)


#### Experimental

Heterogenize PHP's data structures, like `array` that is split into `Seq`, `Dict` and `Tuple`.

- `Seq` is a no-key `array`, setting keys that is not an integer will result in an `Exception`, every `Seq` will be JSON encoded as `[]`;

- `Dict` is a keyed `array`, whether key is string or not, every `Dict` will be JSON encoded as `{}`;

- `Tuple` is just like a `Seq`, maybe in the future helpers will be added to `Tuple`.

Also supplying some helpers for JSON encoding/decoding without headaches on checking whether 
something is `array` or `array` with keys.

__Performance is not guaranteed at this point.__
