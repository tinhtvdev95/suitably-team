<?php
namespace gpweb\shortcodes;

/**
 * * The BaseShortcode class is an abstract class that serves as the base for all shortcodes.
 */
abstract class BaseShortcode
{
  /**
   * * The name of the shortcode.
   *
   * @var string
   */
  protected $name;

  /**
   * * Constructs a new instance of the BaseShortcode class.
   *
   * @param string $name The name of the shortcode.
   */
  public function __construct(string $name)
  {
    $this->name = $name;
  }

  /**
   * * Gets the name of the shortcode.
   *
   * @return string The name of the shortcode.
   */
  public function getShortcodeName()
  {
    return $this->name;
  }

  /**
   * * The callback function for the shortcode.
   *
   * @param array $atts An array of attributes passed to the shortcode.
   * @param mixed $content The content within the shortcode.
   * @return mixed The result of the shortcode callback.
   */
  public abstract function shortcodeCallback(array $atts, $content = null);
}