<?php

namespace Yosigosi\TmxUtils;

class Tools {

  public static function load_xml_from_file($filename) {
    if (!file_exists( $filename )) {
      throw new Exception ( 'File "' . htmlentities ( $filename ) . '" not found.' );
    }
    return simplexml_load_file( $filename );
  }
  
  static function parse_data($data, $encoding = '', $compression = '') {
  
    if ($encoding == 'base64') {
      $data = base64_decode ( $data );
    }
    else if ($encoding == 'csv') {
      $data2 = explode ( chr ( 10 ), $data );
      $data3 = array ();
      $i = 0;
      $data = '';
      foreach ( $data2 as $line ) {
        $line = trim ( $line, " \t\n\r\0\x0B," );
        $data3 [$i] = explode ( ',', $line );
        ++ $i;
      }
      unset ( $line, $data2 );
      $irow = 0;
      $icol = 0;
      $icol2 = 0;
      foreach ( $data3 as $row ) {
        $icol = 0;
        foreach ( $row as $gid ) {
          $data .= pack ( 'V', $gid );
          ++ $icol;
        }
        if ($icol > $icol2) {
          $icol2 = $icol;
        }
        ++ $irow;
      }
      // var_dump($irow,$icol2);
      unset ( $gid, $row, $data3 );
    }
    
    switch (strtolower ( $compression )) {
      case 'zlib' :
        $data = gzuncompress ( $data );
        break;
        
      case 'gzip' :
        $data = gzdecode ( $data );
        break;
        
      case 'bzip2' :
      case 'bz2' :
        $data = bzdecompress ( $data );
        break;
        
      case 'none' :
      default :
        break;
    }
    
    return $data;
  }
  
  static function create_image_from($file) {
    switch (Tools::get_ext( $file )) {
      case 'png' :
        $img = imagecreatefrompng ( $file );
        break;
        /*
         * case 'bmp':
         * $img=imagecreatefrombmp($file);
         * break;
         */
      case 'jpg' :
      case 'jpe' :
      case 'jpeg' :
        $img = imagecreatefromjpeg ( $file );
        break;
      case 'gif' :
        $img = imagecreatefromgif ( $file );
        break;
      default :
        trigger_error ( 'empty image.', E_USER_NOTICE );
        $img = imagecreatetruecolor ( 0, 0 );
        break;
    }
    return $img;
  }
  
  
  static function get_ext($filename) {
    @assert ( strpos ( $filename, '.' ) !== FALSE ) or die ( 'no extension.' );
    return substr ( $filename, strrpos ( $filename, '.' ) + 1 );
  }
  
  static function image_copy_and_resize($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w = NULL, $src_h = NULL) {
    global $quality;
    if ($src_w == NULL && $src_h == NULL) {
      $src_w = $dst_w;
      $src_h = $dst_h;
      return imagecopy ( $dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h );
    } else if ($quality == 0) {
      return imagecopyresized ( $dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );
    } elseif ($quality == 1) {
      return imagecopyresampled ( $dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );
    } else {
      trigger_error ( 'no/bad quality setting.', E_USER_ERROR );
      return false;
    }
  }
  
  static function my_transparent($img, $r, $g, $b, $trans) {
    $x = imagesx ( $img );
    $y = imagesy ( $img );
    for($i = 0; $i < $y; ++ $i) {
      for($j = 0; $j < $x; ++ $j) {
        $rgb = imagecolorat ( $img, $j, $i );
        $_r = ($rgb >> 16) & 0xFF;
        $_g = ($rgb >> 8) & 0xFF;
        $_b = $rgb & 0xFF;
        if ($r == $_r && $g == $_g && $b == $_b) {
          imagesetpixel ( $img, $j, $i, $trans );
        }
      }
    }
  }
  
  static function rotate90cw_lid($lid) {
    assert ( $lid >= 0 && $lid < 6 * 9 );
    // if($lid==-1) return -1;
    if ($lid == 0) {
      return 0;
    }
    if ($lid > 0 && $lid < 9) {
      return - 1;
    }
    $x = ( int ) ($lid % 9);
    $y = ( int ) ($lid / 9);
    // var_dump($x,$y);die();
    // angles
    if ($y == 0 && $x == 0) { /* do nothing */
    } else if ($y == 1 && ($x % 3) == 0) {
      $x += 2;
    } else if ($y == 1 && ($x % 3) == 2) {
      $y += 2;
    } else if ($y == 3 && ($x % 3) == 2) {
      $x -= 2;
    } else if ($y == 3 && ($x % 3) == 0) {
      $y -= 2;
    } // bords
    else if ($y == 1 && ($x % 3) == 1) {
      $x += 1;
      $y += 1;
    } else if ($y == 2 && ($x % 3) == 2) {
      $x -= 1;
      $y += 1;
    } else if ($y == 3 && ($x % 3) == 1) {
      $x -= 1;
      $y -= 1;
    } else if ($y == 2 && ($x % 3) == 0) {
      $x += 1;
      $y -= 1;
    } // middles
    else if ($y == 2 && ($x % 3) == 1) { /* do nothing */
    } // hole angles
    else if ($y == 4 && ($x % 2) == 0) {
      $x += 1;
    } else if ($y == 4 && ($x % 2) == 1) {
      $y += 1;
    } else if ($y == 5 && ($x % 2) == 1) {
      $x -= 1;
    } else if ($y == 5 && ($x % 2) == 0) {
      $y -= 1;
    }
    return $x + $y * 9;
  }
  
  static function rotate90ccw_lid($lid) {
    assert ( $lid >= 0 && $lid < 6 * 9 );
    // if($lid==-1) return -1;
    if ($lid == 0) {
      return 0;
    }
    if ($lid > 0 && $lid < 9) {
      return - 1;
    }
    $x = ( int ) ($lid % 9);
    $y = ( int ) ($lid / 9);
    // var_dump($x,$y);die();
    // angles
    if ($y == 0 && $x == 0) { /* do nothing */
    } else if ($y == 1 && ($x % 3) == 0) {
      $y += 2;
    } else if ($y == 1 && ($x % 3) == 2) {
      $x -= 2;
    } else if ($y == 3 && ($x % 3) == 2) {
      $y -= 2;
    } else if ($y == 3 && ($x % 3) == 0) {
      $x += 2;
    } // bords
    else if ($y == 1 && ($x % 3) == 1) {
      $x -= 1;
      $y += 1;
    } else if ($y == 2 && ($x % 3) == 2) {
      $x -= 1;
      $y -= 1;
    } else if ($y == 3 && ($x % 3) == 1) {
      $x += 1;
      $y -= 1;
    } else if ($y == 2 && ($x % 3) == 0) {
      $x += 1;
      $y += 1;
    } // middles
    else if ($y == 2 && ($x % 3) == 1) { /* do nothing */
    } // hole angles
    else if ($y == 4 && ($x % 2) == 0) {
      $y += 1;
    } else if ($y == 4 && ($x % 2) == 1) {
      $x -= 1;
    } else if ($y == 5 && ($x % 2) == 1) {
      $y -= 1;
    } else if ($y == 5 && ($x % 2) == 0) {
      $x += 1;
    }
    return $x + $y * 9;
  }
  
  static function rotate180_lid($lid) {
    assert ( $lid >= 0 && $lid < 6 * 9 );
    // if($lid==-1) return -1;
    if ($lid == 0) {
      return 0;
    }
    if ($lid > 0 && $lid < 9) {
      return - 1;
    }
    $x = ( int ) ($lid % 9);
    $y = ( int ) ($lid / 9);
    if ($y == 0 && $x == 0) { /* do nothing */
    } else if ($y == 1 && ($x % 3) == 0) {
      $x += 2;
      $y += 2;
    } else if ($y == 1 && ($x % 3) == 2) {
      $x -= 2;
      $y += 2;
    } else if ($y == 3 && ($x % 3) == 2) {
      $x -= 2;
      $y -= 2;
    } else if ($y == 3 && ($x % 3) == 0) {
      $x += 2;
      $y -= 2;
    } // bords
    else if ($y == 1 && ($x % 3) == 1) {
      $y += 2;
    } else if ($y == 2 && ($x % 3) == 2) {
      $x -= 2;
    } else if ($y == 3 && ($x % 3) == 1) {
      $y -= 2;
    } else if ($y == 2 && ($x % 3) == 0) {
      $x += 2;
    } // middles
    else if ($y == 2 && ($x % 3) == 1) { /* do nothing */
    } // hole angles
    else if ($y == 4 && ($x % 2) == 0) {
      $x += 1;
      $y += 1;
    } else if ($y == 4 && ($x % 2) == 1) {
      $x -= 1;
      $y += 1;
    } else if ($y == 5 && ($x % 2) == 1) {
      $x -= 1;
      $y -= 1;
    } else if ($y == 5 && ($x % 2) == 0) {
      $x += 1;
      $y -= 1;
    }
    return $x + $y * 9;
  }
  
  static function swap_array_items(&$var, $pos1, $pos2) {
    $tmp = $var[$pos1];
    $var[$pos1] = $var [$pos2];
    $var[$pos2] = $tmp;
  }
  
}

