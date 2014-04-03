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
	 * CONSTRUCTOR
	 */
	public function __construct() {
		$this->cacheFactory = new CacheFactory('production', $this);
		$this->flushCaches();
	}

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
		if (TRUE === isset($this->cacheConfigurations[$identifier]['frontend'])) {
			$frontend = $this->cacheConfigurations[$identifier]['frontend'];
		} else {
			$frontend = $this->defaultCacheConfiguration['frontend'];
		}
		if (TRUE === isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][$frontend]['className'])) {
			$frontend = $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][$frontend]['className'];
			$backend = 'TYPO3\\CMS\\Core\\Cache\\Backend\\NullBackend';
		} elseif (TRUE === isset($this->cacheConfigurations[$identifier]['backend'])) {
			$backend = $this->cacheConfigurations[$identifier]['backend'];
		} else {
			$backend = $this->defaultCacheConfiguration['backend'];
		}
		$backendOptions = array();
		$this->cacheFactory->create($identifier, $frontend, $backend, $backendOptions);
		$this->caches[$identifier]->getBackend()->setDefaultLifetime(1);
	}

}