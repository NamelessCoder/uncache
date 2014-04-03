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
	if (TRUE === version_compare(TYPO3_branch, '6.2.0', '>=')) {
		$configurations = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($GLOBALS['typo3CacheManager'], 'cacheConfigurations', TRUE);
		$caches = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($GLOBALS['typo3CacheManager'], 'caches', TRUE);
		$GLOBALS['typo3CacheManager'] = new \FluidTYPO3\Uncache\Override\Core\Cache\CacheManager();
		$GLOBALS['typo3CacheManager']->setCacheConfigurations($configurations);
		\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($GLOBALS['typo3CacheManager'], 'caches', $caches, TRUE);
		\TYPO3\CMS\Core\Utility\GeneralUtility::setSingletonInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager', $GLOBALS['typo3CacheManager']);
		unset($configurations, $caches);
	} else {
		$typo3CacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Cache\CacheManager');
		$configurations = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($typo3CacheManager, 'cacheConfigurations', TRUE);
		if (FALSE === isset($configurations['extbase_datamapfactory_datamap'])) {
			$configurations['extbase_datamapfactory_datamap'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($typo3CacheManager, 'defaultCacheConfiguration', TRUE);
		}
		if (FALSE === isset($configurations['extbase_typo3dbbackend_tablecolumns'])) {
			$configurations['extbase_typo3dbbackend_tablecolumns'] = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($typo3CacheManager, 'defaultCacheConfiguration', TRUE);
		}
		if (FALSE === isset($configurations['fluid_template'])) {
			$configurations['fluid_template'] = $configurations['cache_core'];
		}
		if (FALSE === isset($configurations['extbase_typo3dbbackend_queries'])) {
			$configurations['extbase_typo3dbbackend_queries'] = array();
		}
		$caches = \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($typo3CacheManager, 'caches', TRUE);
		$typo3CacheManager = new \FluidTYPO3\Uncache\Override\Core\Cache\CacheManager();
		$typo3CacheManager->setCacheConfigurations($configurations);
		\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($typo3CacheManager, 'caches', $caches, TRUE);
		\TYPO3\CMS\Core\Utility\GeneralUtility::setSingletonInstance('TYPO3\CMS\Core\Cache\CacheManager', $typo3CacheManager);
		unset($configurations, $caches);
	}
}