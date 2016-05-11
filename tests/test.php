<?php

require_once(__DIR__ . '/../vendor/autoload.php');

$pg = new \PhotoGps\PhotoGps(__DIR__ . "/../test-1.jpg");

var_dump($pg->coordinate());
/*
array(2) {
    ["longitude"]=>
  float(113.92439166667)
  ["latitude"]=>
  float(22.529294444444)
}
*/

//图片不包含定位信息
$pg->setFilename(__DIR__ . "/../test-2.jpg");

var_dump($pg->coordinate()); //bool(false)