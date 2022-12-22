<?php
/**
 * Plain Page Helper
 *
 * SPDX-License-Identifier: MIT
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

				$data['page_title'] = 'OpenTHC :: API';

				$file = 'home.php';

				break;
		}

		return $RES->write( $this->render($file, $data) );

	}

}
