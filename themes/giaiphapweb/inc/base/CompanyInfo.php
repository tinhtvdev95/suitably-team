<?php
/**
 * @package Giaiphapweb_Theme
 * * Get and return company info.
 */
namespace gpweb\inc\base;
class CompanyInfo {
  private $address;
  private $phone;
  private $email;
  private array $socials;
  function __construct() {
    $this->getCompanyInfo();
  }
  private function getCompanyInfo() {
    $this->address = get_field('address', 'company_info');
    $this->phone = get_field('phone_number', 'company_info');
    $this->email = get_field('email', 'company_info');
    $this->socials = get_field('socials', 'company_info');
  }

  public function getAddress($hasIcon = false) {
    if($hasIcon) {
      return sprintf('<div class="company-info__item address"><span class="material-symbols-outlined">location_on</span><span>%s</span></div>', $this->address);
    }
    return sprintf('<span>%s</span>', $this->address);
  }
  public function getPhone($hasIcon = false) {
    $trimmedPhone = preg_replace('/\D/', '', $this->phone);
    if($hasIcon) {
      return sprintf('<div class="company-info__item phone-number"><span class="material-symbols-outlined">phone</span><a href="tel:%s">%s</a></div>', $trimmedPhone, $this->phone);
    }
    return sprintf('<a href="tel:%s">%s</a>', $trimmedPhone, $this->phone);
  }
  public function getEmail($hasIcon = false) {
    if($hasIcon) {
      return sprintf('<div class="company-info__item email"><span class="material-symbols-outlined">email</span><a href="mailto:%s">%s</a></div>', $this->email, $this->email);
    }
    return sprintf('<a href="mailto:%s">%s</a>', $this->email, $this->email);
  }
}