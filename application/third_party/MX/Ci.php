<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class CI
{
    public static $APP;
    public function __construct()
    {
        self::$APP =& get_instance();
    }
}
