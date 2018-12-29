<?php

namespace Tmx\Utils;

class Layer {
  
  use PropertiesTrait;
  
  // attributes
  public $name = '';
  public $x = 0;
  public $y = 0;
  public $width = 0;
  public $height = 0;
  public $opacity = 1;
  public $visible = 1;

  protected $map = NULL;
  
  // methods
  public function setMap(Map $map) {
    $this->map = $map;
  }
  public function getMap() {
    return $this->map;
  }
}
