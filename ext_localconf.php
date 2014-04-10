<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Cache\\Frontend\\PhpFrontend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['t3lib_cache_frontend_PhpFrontend'] =
	array('className' => 'FluidTYPO3\\Uncache\\Override\\Core\\Cache\\Frontend\\PhpFrontend');
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Cache\\Frontend\\VariableFrontend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['t3lib_cache_frontend_VariableFrontend'] =
	array('className' => 'FluidTYPO3\\Uncache\\Override\\Core\\Cache\\Frontend\\VariableFrontend');
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Cache\\Frontend\\StringFrontend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['t3lib_cache_frontend_StringFrontend'] =
	array('className' => 'FluidTYPO3\\Uncache\\Override\\Core\\Cache\\Frontend\\StringFrontend');
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['TYPO3\\CMS\\Core\\Cache\\CacheManager'] =
	array('className' => 'FluidTYPO3\\Uncache\\Override\\Core\\Cache\\CacheManager');


if (TRUE === isset($GLOBALS['typo3CacheManager'])) {
	if (TRUE === version_compare(TYPO3_version, '6.2.0', '>=')) {
		\FluidTYPO3\Uncache\Core::setCachesForCoreVersion62();
	} elseif (TRUE === version_compare(TYPO3_version, '6.1.0', '>=')) {
		\FluidTYPO3\Uncache\Core::setCachesForCoreVersion61();
	}
}