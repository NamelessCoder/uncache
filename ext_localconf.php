<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

foreach (array_keys($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']) as $configurationName) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['backend']
        = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
}
