<?php
namespace FluidTYPO3\Uncache;
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

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Compatibility\GlobalObjectDeprecationDecorator;
use TYPO3\CMS\Core\Core\Bootstrap;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Uncache Core
 *
 * Contains the methods necessary to replace caching framework
 * backends and frontends with instances which ignore lifetime
 * and which do not write cached variables.
 *
 * @package FluidTYPO3\Uncache
 */
class Core {

	/**
	 * @return void
	 */
	public static function setCachesForCoreVersion62() {
		/** @var CacheManager $cacheManager */
		$cacheManager = $GLOBALS['typo3CacheManager'];
		if (TRUE === $cacheManager instanceof GlobalObjectDeprecationDecorator) {
			$cacheManager = GeneralUtility::makeInstance('TYPO3\CMS\Core\Cache\CacheManager');
		}
		$defaultConfigurations = ObjectAccess::getProperty($cacheManager, 'defaultCacheConfiguration', TRUE);
		$configurations = ObjectAccess::getProperty($cacheManager, 'cacheConfigurations', TRUE);
		$cacheFactory = ObjectAccess::getProperty($cacheManager, 'cacheFactory', TRUE);
		$caches = ObjectAccess::getProperty($cacheManager, 'caches', TRUE);

		$propertyReflection = new \ReflectionClass('TYPO3\CMS\Core\Utility\GeneralUtility');
		$staticProperties =  $propertyReflection->getStaticProperties();

		if (TRUE === isset($staticProperties['finalClassNameCache']['TYPO3\CMS\Core\Cache\CacheManager'])) {
			$staticProperties['finalClassNameCache']['TYPO3\CMS\Core\Cache\CacheManager'] = 'FluidTYPO3\Uncache\Override\Core\Cache\CacheManager';
			$reflection = new \ReflectionProperty('TYPO3\CMS\Core\Utility\GeneralUtility', 'finalClassNameCache');
			$reflection->setAccessible(TRUE);
			$reflection->setValue(NULL, $staticProperties['finalClassNameCache']);
		}

		$cacheManager = GeneralUtility::makeInstance('FluidTYPO3\Uncache\Override\Core\Cache\CacheManager');
		$cacheManager->injectCacheFactory($cacheFactory);
		$cacheManager->setCacheConfigurations($configurations);

		ObjectAccess::setProperty($cacheFactory, 'cacheManager', $cacheManager, TRUE);

		$GLOBALS['typo3CacheManager'] = $cacheManager;

		/** @var Bootstrap $bootstrap */
		$bootstrap = Bootstrap::getInstance();
		$bootstrap->setEarlyInstance('TYPO3\CMS\Core\Cache\CacheManager', $cacheManager);

		ObjectAccess::setProperty($cacheManager, 'caches', $caches, TRUE);
		ObjectAccess::setProperty($cacheManager, 'defaultCacheConfiguration', $defaultConfigurations, TRUE);
		GeneralUtility::setSingletonInstance('TYPO3\CMS\Core\Cache\CacheManager', $cacheManager);
	}

	/**
	 * @return void
	 */
	public static function setCachesForCoreVersion61() {
		$typo3CacheManager = GeneralUtility::makeInstance('TYPO3\CMS\Core\Cache\CacheManager');
		$configurations = ObjectAccess::getProperty($typo3CacheManager, 'cacheConfigurations', TRUE);
		$defaultConfiguration = ObjectAccess::getProperty($typo3CacheManager, 'defaultCacheConfiguration', TRUE);
		if (FALSE === isset($configurations['extbase_datamapfactory_datamap'])) {
			$configurations['extbase_datamapfactory_datamap'] = $defaultConfiguration;
		}
		if (FALSE === isset($configurations['extbase_typo3dbbackend_tablecolumns'])) {
			$configurations['extbase_typo3dbbackend_tablecolumns'] = $defaultConfiguration;
		}
		if (FALSE === isset($configurations['fluid_template'])) {
			$configurations['fluid_template'] = $configurations['cache_core'];
		}
		if (FALSE === isset($configurations['extbase_typo3dbbackend_queries'])) {
			$configurations['extbase_typo3dbbackend_queries'] = array();
		}
		$caches = ObjectAccess::getProperty($typo3CacheManager, 'caches', TRUE);
		/** @var $typo3CacheManager Override\Core\Cache\CacheManager */
		$uncacheCacheManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager')->get('FluidTYPO3\Uncache\Override\Core\Cache\CacheManager');
		$uncacheCacheManager->setCacheConfigurations($configurations);
		ObjectAccess::setProperty($uncacheCacheManagerCacheManager, 'caches', $caches, TRUE);
		GeneralUtility::setSingletonInstance('TYPO3\CMS\Core\Cache\CacheManager', $uncacheCacheManager);
	}

}