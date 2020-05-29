<?php
/**
 * threecx - hooks:
 *
 * @link http://www.egroupware.org
 * @author Enver Morinaj
 * @package threecx
 * @copyright (c) 2005-11 by Ralf Becker <RalfBecker-AT-outdoor-training.de>
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @version $Id: class.threecx_ui.inc.php $
 */

use EGroupware\Api\Egw;
use EGroupware\Api\Link;

class threecx_hooks {
	/**
	* Hook called by link-class to include test in the appregistry of the linkage
	*
	* @param array/string $location location and other parameters (not used)
	* @return array with method-names
	*/
	static function search_link($location) {

		unset($location);
		return array(
			'title' => 'threecx.threecx_bo.link_title',
                        'query' => 'threecx.threecx_bo.link_query',
			'view'  => array(
		           'menuaction' => 'threecx.threecx_ui.index',
		           ),
			'view_id' => 'threecx_id',
			'view_popup' => '850x590',
			'edit_popup' => '850x590',
			'index' => array(
				'menuaction' => 'threecx.threecx_ui.index',
				),
			'edit' => array(
				'menuaction' => 'threecx.threecx_ui.edit',
					),
			'edit_id'    => 'threecx_id',
			'name'     => 'threecx',                   
		);
	}
	// To register all hooks for the app. on the proper location 
	static function all_hooks($args) {
		//var_dump(debug_print_backtrace());
		$appname = 'threecx';
		$title = lang($GLOBALS['egw_info']['apps'][$appname]['title']);
		$location = is_array($args) ? $args['location'] : $args;
		// echo "<p>ts_admin_prefs_sidebox_hooks::all_hooks(".print_r($args,True).") appname='$appname', location='$location'</p>\n";
                

        if ($location == 'sidebox_menu') {
            if (($GLOBALS['egw_info']['user']['apps']['admin'] && $location != 'admin') || ($GLOBALS['egw_info']['user']['account_id'] == '116')) {
            	$file = array(
	            	'Anrufsliste'			=> Egw::link("/egroupware/threecx/graph/calllist.php"),
	            	'Einstellungen'			=> Egw::link("/egroupware/threecx/graph/settings.php"),
            	);

            	display_sidebox($appname, "Administrator", $file);
            }
        }

		if ($GLOBALS['egw_info']['user']['apps']['admin'] && $location != 'preferences') {
			$file = array();
			
            if ($location == 'admin')
			{
				display_section($appname,$file);
			}
			else
			{
				display_sidebox($appname,lang('Admin'),$file);
			}
		}
	}	
}
