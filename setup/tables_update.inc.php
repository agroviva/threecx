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

function threecx_upgrade16_1_0()
{
	$GLOBALS['egw_setup']->oProc->CreateTable('egw_threecx_calllist',array(
		'fd' => array(
			'CallID' => array('type' => 'text','precision' => '255','nullable' => False),
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
	));

	return $GLOBALS['setup_info']['threecx']['currentver'] = '16.1.001';
}


function threecx_upgrade16_1_001()
{
	$GLOBALS['egw_setup']->oProc->AlterColumn('egw_threecx_calllist','CallID',array(
		'type' => 'varchar',
		'precision' => '255',
		'nullable' => False
	));

	return $GLOBALS['setup_info']['threecx']['currentver'] = '16.1.002';
}

