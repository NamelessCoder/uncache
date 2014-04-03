<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "uncache".
 *
 * Auto generated 11-08-2013 22:33
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Uncache: Caching Framework Crippler',
	'description' => 'You know caches. You hate them while developing - when installed, this extension prevents them all from working. NOT FOR PRODUCTION USE!',
	'category' => 'misc',
	'shy' => 0,
	'version' => '1.0.1',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'FluidTYPO3 Team',
	'author_email' => 'claus@namelesscoder.net',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.0.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:7:{s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"6783";s:9:"README.md";s:4:"2f68";s:44:"Classes/Override/Core/Cache/CacheManager.php";s:4:"d823";s:52:"Classes/Override/Core/Cache/Frontend/PhpFrontend.php";s:4:"0f31";s:55:"Classes/Override/Core/Cache/Frontend/StringFrontend.php";s:4:"ebab";s:57:"Classes/Override/Core/Cache/Frontend/VariableFrontend.php";s:4:"898a";}',
	'suggests' => array(
	),
);
