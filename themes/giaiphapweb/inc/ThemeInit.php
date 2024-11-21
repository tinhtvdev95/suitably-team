<?php
/**
 * Theme init Class
 */
namespace gpweb\inc;

class ThemeInit {
  /**
   ** Get the list of services to be registered.
   *
   * @return array The list of services.
   */
  public static function get_services() {
    return [
      ChangeLoginPage::class,
      base\Register::class,
    ];
  }

  /**
   ** Register the services.
   */
  public static function register_services() {
    foreach(self::get_services() as $class) {
      $service = self::instantiate($class);
      if(method_exists($service, 'register')) {
        $service->register();
      }
    }
  }

  /**
   ** Instantiate a service class.
   *
   * @param string $class The class name.
   * @return object An instance of the class.
   */
  private static function instantiate($class) {
    return new $class();
  }
}