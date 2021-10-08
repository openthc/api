<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 *
 * Product Type Data Viewer
 */

namespace App\Controller;

class BaseData extends \OpenTHC\Controller\Base
{
	function __invoke($REQ, $RES, $ARG)
	{

		$data = [
			'page_title' => 'OpenTHC Base Data'
			, 'company_type_list' => $this->_get_company_type_list()
			, 'license_type_list' => $this->_get_license_type_list()
			, 'contact_type_list' => $this->_get_contact_type_list()
			, 'product_type_list' => $this->_get_product_type_list()
			, 'lab_metric_type_list' => $this->_get_lab_metric_type_list()
			, 'lab_metric_list' => $this->_get_lab_metric_list()
		];

		return $RES->write( $this->render('base-data', $data) );

	}

	/**
	 *
	 */
	function _get_company_type_list()
	{
		$src_glob = sprintf('%s/etc/company-type/*.yaml', APP_ROOT);
		$src_list = glob($src_glob);
		return $this->_load_yaml_data($src_list);
	}

	/**
	 *
	 */
	function _get_contact_type_list()
	{
		$src_glob = sprintf('%s/etc/contact-type/*.yaml', APP_ROOT);
		$src_list = glob($src_glob);
		return $this->_load_yaml_data($src_list);
	}

	/**
	 *
	 */
	function _get_license_type_list()
	{
		$src_glob = sprintf('%s/etc/license-type/*.yaml', APP_ROOT);
		$src_list = glob($src_glob);
		return $this->_load_yaml_data($src_list);
	}

	/**
	 *
	 */
	function _get_lab_metric_type_list()
	{
		$src_glob = sprintf('%s/etc/lab-metric-type/*.yaml', APP_ROOT);
		$src_list = glob($src_glob);
		return $this->_load_yaml_data($src_list);
	}

	/**
	 *
	 */
	function _get_lab_metric_list()
	{
		$src_glob = sprintf('%s/etc/lab-metric/*.yaml', APP_ROOT);
		$src_list = glob($src_glob);
		return $this->_load_yaml_data($src_list);
	}

	/**
	 *
	 */
	function _get_product_type_list()
	{
		$src_glob = sprintf('%s/etc/product-type/*.yaml', APP_ROOT);
		$src_list = glob($src_glob);
		return $this->_load_yaml_data($src_list);
	}

	/**
	 *
	 */
	function _load_yaml_data($src_list)
	{
		foreach ($src_list as $file) {

			$obj = yaml_parse_file($file);
			// $obj['link'] = sprintf('/product-type/%s', $obj['id']);
			$obj['sort'] = intval($obj['sort']);
			$obj_list[] = $obj;

		}

		usort($obj_list, function($a, $b) {
			if ($a['sort'] == $b['sort']) {
				return strcmp($a['name'], $b['name']);
			}
			return ($a['sort'] > $b['sort']);
		});

		return $obj_list;
	}

}
