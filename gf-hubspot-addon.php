<?php 
/*
Plugin Name: Gravity Forms Hubspot Form Submission Add-On
Plugin URI: http://www.thedevcave.com
Description: Add-on for Gravity Forms allowing you to submit any Gravity Form to a duplicate form in Hubspot to automatically sync your submissions.
Version: 1.0
Author: TheDevCave
Author URI: http://www.thedevcave.com

------------------------------------------------------------------------
Copyright 2022 TheDevCave

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

define( 'GF_HUBSPOT_ADDON_VERSION', '1.0' );
define( 'GF_HUBSPOT_ADDON_DIR', __DIR__ );
define( 'GF_HUBSPOT_ADDON_URL', plugin_dir_url( __FILE__ ) );
 
add_action( 'gform_loaded', array( 'GF_Hubspot_AddOn_Bootstrap', 'load' ), 5 );
 
class GF_Hubspot_AddOn_Bootstrap {
 
		public static function load() {
 
				if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
						return;
				}
 
 				require_once( 'vendor/autoload.php' );
				require_once( 'lib/actions/oauth.php' );
				require_once( 'lib/classes/class-GFHubspotAddOn.php' );
				require_once( 'lib/classes/class-OAuth2Helper.php' );
				require_once( 'lib/classes/class-HubspotClientHelper.php' );
 
				GFAddOn::register( 'GFHubspotAddOn' );
		}
 
}
 
function gf_simple_addon() {
		return GFHubspotAddOn::get_instance();
}