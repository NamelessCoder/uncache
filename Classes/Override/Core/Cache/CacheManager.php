<?php
namespace FluidTYPO3\Uncache\Override\Core\Cache;
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

use TYPO3\CMS\Core\Cache\CacheFactory;
use TYPO3\CMS\Core\SingletonInterface;

/**
 * Class CacheManager
 * @package FluidTYPO3\Uncache
 */
class CacheManager extends \TYPO3\CMS\Core\Cache\CacheManager implements SingletonInterface {

	/**
	 * @param string $identifier
	 * @return \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface
	 */
	public function getCache($identifier) {
		if (FALSE === $this->hasCache($identifier)) {
			$this->createCache($identifier);
		}
		return parent::getCache($identifier);
	}

	/**
	 * Instantiates the cache for $identifier.
	 *
	 * @param string $identifier
	 * @return void
	 */
	protected function createCache($identifier) {
		if (isset($this->cacheConfigurations[$identifier]['frontend'])) {
			$frontend = $this->cacheConfigurations[$identifier]['frontend'];
		} else {
			$frontend = $this->defaultCacheConfiguration['frontend'];
		}

		if (isset($this->cacheConfigurations[$identifier]['backend'])
			&& isset($this->cacheConfigurations[$identifier]['backend']['frontend'])
		) {
			/** @var \TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService */
			$reflectionService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Reflection\\ReflectionService');
			$phpBackendRequired = $reflectionService->hasMethod($this->cacheConfigurations[$identifier]['frontend'], 'requireOnce');
		}

		if ($phpBackendRequired) {
			$backend = 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend';
		} else {
			$backend = 'FluidTYPO3\\Uncache\\Cache\\Backend\\TransientMemoryBackend';
		}

		$backendOptions = $this->defaultCacheConfiguration['options'];

		// Add the cache identifier to the groups that it should be attached to, or use the default ones.
		if (isset($this->cacheConfigurations[$identifier]['groups']) && is_array($this->cacheConfigurations[$identifier]['groups'])) {
			$assignedGroups = $this->cacheConfigurations[$identifier]['groups'];
		} else {
			$assignedGroups = $this->defaultCacheConfiguration['groups'];
		}
		foreach ($assignedGroups as $groupIdentifier) {
			if (!isset($this->cacheGroups[$groupIdentifier])) {
				$this->cacheGroups[$groupIdentifier] = array();
			}
			$this->cacheGroups[$groupIdentifier][] = $identifier;
		}

		$this->cacheFactory->create($identifier, $frontend, $backend, $backendOptions);
	}

}