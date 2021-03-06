<?php

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt, and/or GPL license.
 *
 * For more information please see http://nette.org
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette
 */



/**
 * Check and reset PHP configuration.
 */
error_reporting(E_ALL | E_STRICT);
@set_magic_quotes_runtime(FALSE); // intentionally @
iconv_set_encoding('internal_encoding', 'UTF-8');
extension_loaded('mbstring') && mb_internal_encoding('UTF-8');
header('X-Powered-By: Nette Framework');



/**
 * Load and configure Nette Framework
 */
define('NETTE', TRUE);
define('NETTE_VERSION_ID', 10000); // v1.0.0
define('NETTE_PACKAGE', '5.3');



require_once __DIR__ . '/Utils/shortcuts.php';
require_once __DIR__ . '/Utils/exceptions.php';
require_once __DIR__ . '/Utils/Framework.php';
require_once __DIR__ . '/Utils/Object.php';
require_once __DIR__ . '/Utils/ObjectMixin.php';
require_once __DIR__ . '/Utils/Callback.php';
require_once __DIR__ . '/Loaders/LimitedScope.php';
require_once __DIR__ . '/Loaders/AutoLoader.php';
require_once __DIR__ . '/Loaders/NetteLoader.php';


Nette\Loaders\NetteLoader::getInstance()->base = __DIR__;
Nette\Loaders\NetteLoader::getInstance()->register();
