<?php

namespace App\Helpers;

class HTMLTagsCleaner
{
  /**
   * Undocumented function
   *
   * @param string $str
   * @return void
   */
  public static function cleaner(string $str)
  {
    return preg_replace("/&#?[a-z0-9]+;/i","", $str);
  }
}
