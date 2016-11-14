<?php

/*
 * Logic Functions
 * All Site function must be put in here for easy control
 */
namespace App\Garena;

use App\Account;
use App\SubMenu;

class Functions
{
    /**
     * Hard code function using for testing.
     * We also must comment in app\Http\Kernel.php
     * about \App\Http\Middleware\VerifyCsrfToken
     * @param null $uid
     */
    public static $resultMenuRecursive = '';

    public static function hardLogin($uid = null)
   {
       if (!$uid) {
           $uid = random_int(1, 111111);
       }

       $accounts = Account::firstOrCreate([
           'uid' => $uid
       ], [
           'username' => md5($uid),
           'email' => ''
       ]);

       auth('frontend')->login($accounts, true);
   }

   public static function getMenuRecursive($menu)
   {
       $subMenu = SubMenu::where('id', $menu)->first();


       self::$resultMenuRecursive = self::$resultMenuRecursive.'/'.  $subMenu->order;

       if($subMenu->parent_type == 2) {

           $parentOrder = SubMenu::where('id', $subMenu->parent)->first();
           self::$resultMenuRecursive = $parentOrder->order . '/' . self::$resultMenuRecursive;
           self::getMenuRecursive($parentOrder->id);
       } else {
           $result = self::$resultMenuRecursive = $subMenu->parent . '/' . self::$resultMenuRecursive;
           self::$resultMenuRecursive = '';
           return $result;
       }
   }
}