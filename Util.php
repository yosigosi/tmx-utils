<?php

namespace Drupal\tmx\Library;

class Util {
  static function buildImage($file = '', $zoom = 1, $rot = '') {
    
    $file = !empty($file)? $file : '../../maps/desert3.tmx';

    if ( !is_numeric ( $zoom ) || ($zoom < 0.1) || ($zoom > 10) ) {
      $zoom = 1;
    }

    if (! in_array ( $rot, array ('0', 'ccw', '90', '180', 'cw', '270', '360') ) ) {
      $rot = '';
    }
    
    ini_set ( 'error_reporting', E_ALL | E_NOTICE | E_STRICT | E_RECOVERABLE_ERROR | E_DEPRECATED | E_USER_DEPRECATED | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE );
    ini_set ( 'display_errors', 1 );
    ini_set ( 'display_startup_errors', 1 );
    ini_set ( 'ignore_repeated_errors', 0 );
    ini_set ( 'track_errors', 1 );

    ob_start ();

    libxml_use_internal_errors ( true );

    session_start ();


    $map = new Map ();

    $res = $map->load ( $file );

    $viewer = new Viewer ();

    $viewer->setMap ( $map, $rot );

    ini_set ( 'output_buffering', 'off' );

    $data = ob_get_clean ();
    if (! empty ( $data )) {
      header ( 'Content-Type: text/plain' . "\r\n" );
      echo $data;
      die ();
    }

    ob_start ();

    $viewer->load_ts ();

    $viewer->setZoom( $zoom );

    $viewer->init_draw ();
    $viewer->draw ();

    $data = ob_get_contents ();
    if (strlen ( $data ) != 0) {
      header ( 'Content-Type: text/plain' . "\r\n" );
      echo $data;
      die ();
    }

    $viewer->render ();

    $data = ob_get_clean ();
    if (! defined ( 'DEBUG' ) || DEBUG !== true) {
      header ( 'Content-Type: image/png' . "\r\n" );
    }
    echo $data;
    die ();
  }
}
