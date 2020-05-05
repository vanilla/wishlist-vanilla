<?php
/*
Plugin Name: Vanilla Forums Wishlist
Plugin URI: https://vanillaforums.com
Description: Integrates Wishlist with Vanilla Forums SSO on WordPress.
Version: 1.2.2
Author: Vanilla Forums
Author URI: https://vanillaforums.com
*/

/**
 * This extension will add Wishlist level information to Vanilla Formum's SSO.
 * 
 * Here's how to use this extension:
 * 1. Drop this file into wp-content/plugins/wishlist-member/extensions. 
 *    Note that this is NOT a Wordpress plugin, but rather a Wishlist member extension.
 * 2. Install and activate the Vanilla Forums Wordpress plugin. You must be using AT LEAST version 1.1.9 of the plugin.
 * 
 * @copyright Copyright 2008, 2009 Vanilla Forums Inc.
 * @license Proprietary
 */


if (!function_exists('vf_get_user_wishlist')) {
   /**
    * Adds the wishlist levels of the user to their roles so that Vanilla's SSO can grab them.
    * 
    * @param array $user The user we are filtering.
    * @return array
    */
   function vf_get_user_wishlist($user) {
      $levels = WLMAPI::wlmapi_get_member_levels($current_user->ID);

      $wishlistRoles = [];
      foreach ($levels as $level) {
          if ($level->Active && !$level->Cancelled && !$level->Expired) {
              $wishlistRoles[] = $level->Name;  
          }
      }
      if (is_array($levels)) {
         $roles = array();
         if (isset($user['roles']))
            $roles = explode(',', $user['roles']);

         $roles = array_merge($roles, $wishlistRoles);
         $roles = array_unique($roles);
         $user['roles'] = implode(',', $roles);      
      }
      return $user;
   }
   
   // Add the filter.
   add_filter('vf_get_user', 'vf_get_user_wishlist');
}
?>