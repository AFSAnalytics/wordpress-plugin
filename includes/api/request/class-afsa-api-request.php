<?php

require_once AFSA_INCLUDES_DIR . '/api/class-afsa-api.php';

require_once 'class-afsa-api-result.php';

class AFSA_Api_Request {

	private $api;
	private $logged;
	private $requested_actions;

	public function __construct() {

		if ( sanitize_text_field( $_POST['account_id'] ) === AFSA_Config::DEMO_ACCOUNT_ID ) {
			AFSA_Config::set_demo_mode();
		}

		/*
		$_POST['actions'] and  $_POST['context'] are NOT user generated data

		$_POST['actions'] is an array (JSON object) of combined request data that will be sent to
		afsanalytics.com to retrieve statistical data processed to display various charts in our dashboard

		$_POST['context'] is an array (JSON object) of combined data that will be used to enhance
		data received from afsanalytics.com

		Both are produced by https://api.afsanalytics.com/assets/js/common/v2/dashboard.js

		None is directly displayed, or saved.

		$_POST['context']['products] is the only data to be used to request data from db.
		Its content is sanitized in class-afsa-db.php ( get_products_by_ref ).

		*/

		$this->requested_actions = empty( $_POST['actions'] ) ? null : $_POST['actions'];
		$this->context           = empty( $_POST['context'] ) ? null : $_POST['context'];
	}

	public function run() {
		return $this->send_batch();
	}

	private function validate() {
		return ! empty( $this->requested_actions );
	}

	private function login() {
		AFSA_Tools::log( '[WP Plugin] AJAX login' );
		$this->api           = new AFSA_Api();
		return $this->logged = $this->api->is_logged();
	}

	public function logout() {
		$this->api->logout();
		$this->logged = false;
	}

	public function send_batch() {
		AFSA_Tools::log( __METHOD__ );
		AFSA_Tools::log( '[WP Plugin BATCH] actions: ' . json_encode( $this->requested_actions, JSON_PRETTY_PRINT ) );

		$ret = null;
		if ( $this->validate() ) {
			if ( ! $this->login() ) {
				AFSA_Tools::log( '[WP Plugin] not logged' );
				return array( 'error' => 401 );
			}

			$ret = $this->api->post( '/stats/batch', array( 'actions' => $this->requested_actions ) );
		}

		$result = new AFSA_Api_Request_Result( $this, $ret );

		return $result->render();
	}

}
