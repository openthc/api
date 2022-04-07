<?php
/**
 * OpenTHC API - Bootstrap
 *
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 *
 * SPDX-License-Identifier: MIT
 */

define('APP_NAME', 'OpenTHC API');
define('APP_ROOT', __DIR__);

error_reporting(E_ALL & ~ E_NOTICE);

openlog('openthc-api', LOG_ODELAY|LOG_PID, LOG_LOCAL0);

require_once(APP_ROOT . '/vendor/autoload.php');
