<?php
/**
 * OpenTHC API - Bootstrap
 *
 * This file is part of OpenTHC API
 *
 * OpenTHC API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * OpenTHC API is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OpenTHC API.  If not, see <https://www.gnu.org/licenses/>.
 */

define('APP_NAME', 'OpenTHC API');
define('APP_SITE', 'https://api.openthc.dev');
define('APP_ROOT', __DIR__);
define('APP_SALT', sha1(APP_NAME . APP_SITE . APP_ROOT));

error_reporting(E_ALL & ~ E_NOTICE);

openlog('openthc-api', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

require_once(APP_ROOT . '/vendor/autoload.php');
