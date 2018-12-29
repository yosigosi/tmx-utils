php-tmx-viewer -> drupal-tmx-library
====================================
The purpouse of this fork is to include the code into a drupal module that provides a tmx map repository.
Some features that will be lost:
 - User interface code will be removed.
 - Support from php 5.x versións will be removed.
 - Support for non local files will be removed.
 - Custom Error handling will be removed.
Many files will be renamed, just the files needed will be kept.
The first vesion will have no drupal dependencies, the following versions will be dependent on drupal 8.6.x code.

USAGE v1.0 (no drupal dependecies)
==================================
To generate a image from a tmx local file:
 Util::buildImage($tmx_file, $zoom, $rot);
where:
 - $tmx_file: mandatory, the absolute path to the tmx local file.
 - $zoom: optional, a zoom value to apply to the generated image in the range 0.1-10. Default: 1
 - $rot: optional, rotation to apply to the generated image. Allowed values: 0|ccw|90|180|cw|270|360. Default: 0
