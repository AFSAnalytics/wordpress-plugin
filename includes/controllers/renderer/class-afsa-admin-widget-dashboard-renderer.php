<?php

require_once 'class-afsa-admin-widget-renderer.php';

class AFSA_Admin_Widget_Dashboard_Renderer extends AFSA_Dashboard_Renderer {

	public static function should_display() {
		return AFSA_config::afsa_enabled();
	}

	public static function get_dashboard_scripts() {
		return array( 'chart.engine', 'dashboard' );
	}

	public function __construct() {
		$this->embedded = true;
		parent::__construct();
	}

	public function api_login() {
		$this->afsa_api = $api = new AFSA_Api();
		$api->simple_login();
		return $api->is_logged();
	}

	protected function render_js_config() {
		$cfg = parent::render_js_config();
		unset( $cfg['dashboard']['container'] );
		$cfg['dashboard']['do_not_parse'] = 0;
		return $cfg;
	}

	public function render() {

		if ( ! AFSA_Config::afsa_enabled() && ! AFSA_Config::is_demo() ) {
			return '';
		}

		if ( ! $this->api_login() ) {
			AFSA_Tools::log( 'API not logged' );
			return '';
		}

		return '<div id = afsa_dashboard>'
				. $this->render_widget( 'overview' )
				. '</div>'
				. $this->render_js_data()
				. '<script src="' . AFSA_Config::get_url( 'js/admin.widget.js' ) . '"></script>';
	}

}
