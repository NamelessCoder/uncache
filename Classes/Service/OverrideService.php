<?php
namespace FluidTYPO3\Uncache\Service;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Claus Due <claus@namelesscoder.net>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class CacheManager
 * @package FluidTYPO3\Uncache
 */
class OverrideService implements SingletonInterface {

	/**
	 * Swaps the cacheManager of the core for the uncache one
	 * by cloning all configurations and properties
	 *
	 * @return void
	 */
	public static function swapCacheManager() {
		if (TRUE === version_compare(TYPO3_version, '6.2.0', '>=')) {
			/** @var TYPO3\CMS\Core\Cache\CacheManager $cacheManager */
			$cacheManager = $GLOBALS['typo3CacheManager'];
			if ($cacheManager instanceof \TYPO3\CMS\Core\Compatibility\GlobalObjectDeprecationDecorator) {
				$cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager');
			}

			$defaultConfigurations	= \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($cacheManager, 'defaultCacheConfiguration', TRUE);
			$configurations 		= \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($cacheManager, 'cacheConfigurations', TRUE);
			$cacheFactory			= \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($cacheManager, 'cacheFactory', TRUE);
			$caches					= \TYPO3\CMS\Extbase\Reflection\ObjectAccess::getProperty($cacheManager, 'caches', TRUE);

			$propertyReflection = new \ReflectionClass('TYPO3\\CMS\\Core\\Utility\\GeneralUtility');
			$staticProperties =  $propertyReflection->getStaticProperties();

			if (isset($staticProperties['finalClassNameCache']['TYPO3\\CMS\\Core\\Cache\\CacheManager'])) {
				$staticProperties['finalClassNameCache']['TYPO3\\CMS\\Core\\Cache\\CacheManager'] = 'FluidTYPO3\\Uncache\\Override\\Core\\Cache\\CacheManager';
				$reflection = new \ReflectionProperty('TYPO3\\CMS\\Core\\Utility\\GeneralUtility', 'finalClassNameCache');
				$reflection->setAccessible(true);
				$reflection->setValue(null, $staticProperties['finalClassNameCache']);
			}

			$cacheManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('FluidTYPO3\\Uncache\\Override\\Core\\Cache\\CacheManager');
			$cacheManager->injectCacheFactory($cacheFactory);
			$cacheManager->setCacheConfigurations($configurations);

			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($cacheFactory, 'cacheManager', $cacheManager, TRUE);

			$GLOBALS['typo3CacheManager'] = $cacheManager;

			/** @var \TYPO3\CMS\Core\Core\Bootstrap $bootstrap */
			$bootstrap = \TYPO3\CMS\Core\Core\Bootstrap::getInstance();
			$bootstrap->setEarlyInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager', $cacheManager);


			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($cacheManager, 'caches', $caches, TRUE);
			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($cacheManager, 'defaultCacheConfiguration', $defaultConfigurations, TRUE);
			\TYPO3\CMS\Core\Utility\GeneralUtility::setSingletonInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager', $cacheManager);
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
		}
	}

}