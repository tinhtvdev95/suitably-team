<?php
namespace gpweb\inc\base;
abstract class BaseController {
  public $theme_url;
  public $theme_dir;
  public function __construct() {
    $this->theme_url = get_stylesheet_directory_uri();
    $this->theme_dir = get_stylesheet_directory();
  }
}