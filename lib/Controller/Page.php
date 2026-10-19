<?php
/**
 * Plain Page Helper
 *
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under GPL 3.0 License
 * SPDX-License-Identifier: GPL-3.0-only
 */

namespace OpenTHC\API\Controller;

class Page extends \OpenTHC\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$data = [];

		switch ($_SERVER['REQUEST_URI']) {
			case '/home':

				$data['page_title'] = 'API';

				$file = 'home.php';

				break;
		}

		return $RES->write( $this->render($file, $data) );

	}

}
