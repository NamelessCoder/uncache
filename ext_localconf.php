<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'] as $configurationName => $configuration) {
    if (is_a($configuration['backend'], \TYPO3\CMS\Core\Cache\Backend\PhpCapableBackendInterface::class, true)) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['backend']
            = \TYPO3\CMS\Core\Cache\Backend\NullBackend::class;
    } elseif (is_a($configuration['frontend'], \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class, true)) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['backend']
            = \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class;
        unset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['options']);
    } else {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['frontend']
            = \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['backend']
            = \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class;
        unset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$configurationName]['options']);
    }
}
