<?php

namespace Yosigosi\TmxUtils;

class Util {
  
  /**
   * Return an object with an image view of a TMX map.
   *  
   * @param string $file
   * @param number $zoom
   * @param string $rot
   * 
   * @return \stdClass
   * 
   *   Attributes:
   *     - is_error: error flag.
   *     - error_info: error info.
   *     - gd_resource: gd image resource.  
   * 
   */
  static function buildImage($file, $zoom = 1, $rot = '') {

    $image = new \stdClass();
    $image->is_error = TRUE;
    
    $output_buffering_initial = ini_get('output_buffering');
    
    try {
      
      if ( !is_numeric ( $zoom ) || ($zoom < 0.1) || ($zoom > 10) ) {
        $zoom = 1;
      }
  
      if (! in_array ( $rot, array ('0', 'ccw', '90', '180', 'cw', '270', '360') ) ) {
        $rot = '';
      }
      
      ob_start ();
  
      libxml_use_internal_errors ( true );
  
      $map = new Map();

      $res = $map->load( $file );
  
      $viewer = new Viewer ();  
      $viewer->setMap ( $map, $rot );
  
      ini_set ( 'output_buffering', 'off' );
  
      $data = ob_get_clean();
      if (! empty ( $data )) {
        $image->data = $data;
      }
      else {
        ob_start ();
    
        $viewer->load_ts();
        $viewer->setZoom( $zoom );
        $viewer->init_draw();
        $viewer->draw();
        
        $data = ob_get_contents();
        if (strlen ( $data ) != 0) {
          $image->error_info = $data;
        }
        else {
          $image->is_error = FALSE;
          $image->gd_resource = $viewer->getImageResource();
        }
      }
    }
    catch( \Throwable $e ) {
      $image->error_info = $e->__toString();
    }
    
    ini_set ( 'output_buffering', $output_buffering_initial );
    
    return $image;
  }
}
