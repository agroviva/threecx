<?php
/**
 * eGroupWare - Setup
 * http://www.egroupware.org
 * Created by eTemplates DB-Tools written by ralfbecker@outdoor-training.de
 *
 * @license http://opensource.org/licenses/gpl-license.php GPL - GNU General Public License
 * @package threecx
 * @subpackage setup
 * @version $Id$
 */


$phpgw_baseline = array(
	'egw_threecx' => array(
		'fd' => array(
			'id' => array('type' => 'auto','nullable' => False),
			'data' => array('type' => 'varchar','precision' => '255','nullable' => False)
		),
		'pk' => array('id'),
		'fk' => array(),
		'ix' => array(),
		'uc' => array()
	),
	'egw_threecx_meta' => array(
		'fd' => array(
			'id' => array('type' => 'auto','precision' => '11','nullable' => False),
			'meta_name' => array('type' => 'varchar','precision' => '255','nullable' => False),
			'meta_connection_id' => array('type' => 'int','precision' => '11','nullable' => False),
			'meta_data' => array('type' => 'longtext')
		),
		'pk' => array('id'),
		'fk' => array(),
		'ix' => array(),
		'uc' => array()
	),
	'egw_threecx_calllist' => array(
		'fd' => array(
			'CallID' => array('type' => 'varchar','precision' => '255','nullable' => False),
			'CallTime' => array('type' => 'int','precision' => '11'),
			'CallerId' => array('type' => 'varchar','precision' => '255'),
			'Destination' => array('type' => 'varchar','precision' => '255'),
			'Duration' => array('type' => 'int','precision' => '11'),
			'Answered' => array('type' => 'int','precision' => '11')
		),
		'pk' => array(),
		'fk' => array(),
		'ix' => array(),
		'uc' => array('CallID')
	)
);
