<?php

// Prevent loading this file directly
if (!class_exists('WP')) {
   header('Status: 403 Forbidden');
   header('HTTP/1.1 403 Forbidden');
   exit;
}


class XSliderFreeUtils
{
   public static function getOption($key, $falseAsEmpty = false, $allowMultisite = false)
   {
      if (is_multisite() && $allowMultisite)
         $val = get_site_option($key);
      else
         $val = get_option($key);

      return ($val === false) ? ($falseAsEmpty ? "" : false) : $val;
   }

   public static function updateOption($key, $value, $allowMultisite = false)
   {
      if (is_multisite() && $allowMultisite)
         return update_site_option($key, $value);
      else
         return update_option($key, $value);
   }

   public static function preize($t)
   {
      echo "<pre>";
      print_r($t);
      echo "</pre>";
   }

   public static function isMobile()
   {
      if (isset($_SERVER['HTTP_USER_AGENT'])) {
         if (preg_match('!(tablet|pad|mobile|phone|symbian|android|ipod|ios|blackberry|webos)!i', $_SERVER['HTTP_USER_AGENT']))
            return true;
      }
      return false;
   }

   public static function isIPad()
   {
      if (isset($_SERVER['HTTP_USER_AGENT'])) {
         if (preg_match('!(ipad)!i', $_SERVER['HTTP_USER_AGENT']))
            return true;
      }
      return false;
   }

   public static function getIp()
   {
      $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
      else
         $ipaddress = 'UNKNOWN';
      return $ipaddress;
   }
}

