<?php
/**
 *
 */

namespace App\Controller;

class DataModel extends \OpenTHC\Controller\Base
{
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$file = sprintf('%s/webroot/openapi.yaml', APP_ROOT);
		$model_data = yaml_parse_file($file);

		$model_list = $model_data['components']['schemas'];
		// unset($model_list['Response_General']);
		// unset($model_list['Response_Failure']);

		$data = [];
		$data['page_title'] = 'OpenTHC API Data Model';
		$data['model_list'] = [];

		foreach ($model_list as $mk => $mv) {

			if (preg_match('/^(Request|Response)/', $mk)) {
				continue;
			}

			$mv['name'] = $mk;
			$data['model_list'][$mk] = $mv;

		}

		return $RES->write( $this->render('data-model', $data) );

	}

}
