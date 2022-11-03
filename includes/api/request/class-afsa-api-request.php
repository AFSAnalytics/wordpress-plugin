<?php

require_once AFSA_INCLUDES_DIR . '/api/class-afsa-api.php';

require_once 'class-afsa-api-result.php';

class AFSA_Api_Request {

	private $api;
	private $logged;
	private $requested_actions;

	public function __construct() {
		$post_data = &$_POST;

		if ( $post_data['account_id'] === AFSA_Config::DEMO_ACCOUNT_ID ) {
			AFSA_Config::set_demo_mode();
		}

		$this->requested_actions = empty( $post_data['actions'] ) ? null : $post_data['actions'];
		$this->context           = empty( $post_data['context'] ) ? null : $post_data['context'];
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
