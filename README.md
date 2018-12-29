# Tmx/Utils

Tmx/Utils is a PHP 7.0+ library to work with TMX files.

This project forked from [php-tmx-viewer]

The purpouse of this fork is to start a php library for a drupal module that provides a tmx map repository.
Some features from original project are lost:
 - User interface code will be removed.
 - Support from php 5.x versións will be removed.
 - Support for non local files will be removed.
 - Custom Error handling will be removed.
Many original files have been renamed and deleted.


## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```bash
composer require yosigosi/tmx-utils
```

## Requirements

PHP 7.x

## Examples

To generate a image from a tmx local file:
 Util::buildImage($tmx_file, $zoom, $rot);
where:
 - $tmx_file: mandatory, the absolute path to the tmx local file.
 - $zoom: optional, a zoom value to apply to the generated image in the range 0.1-10. Default: 1
 - $rot: optional, rotation to apply to the generated image. Allowed values: 0|ccw|90|180|cw|270|360. Default: 0

## Copyright and license

The Tmx/Utils library is copyright © yosigosi and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[source]: https://github.com/yosigosi/tmx-utils
[release]: https://packagist.org/packages/yosigosi/tmx-utils
[license]: https://github.com/yosigosi/tmx-utils/blob/master/LICENSE

[php-tmx-viewer]: https://github.com/sebbu2/php-tmx-viewer

[packagist]: https://packagist.org/
[composer]: http://getcomposer.org/

