<?php

class AFSA_Site_Infos extends AFSA_Infos {

	public function retrieve() {

		$locale   = get_bloginfo( 'language' );
		$lng      = explode( '-', $locale )[0];
		$currency = AFSA_Config::get_global_currency_code();

		$this->data = array(
			'name'   => esc_js( get_bloginfo( 'name' ) ),
			'desc'   => esc_js( get_bloginfo( 'description' ) ),
			'url'    => site_url(),
			'domain' => parse_url( site_url(), PHP_URL_HOST ),
			'lng'    => $lng,
			'email'  => esc_js( get_bloginfo( 'admin_email' ) ),
			'cms'    => AFSA_Config::CMS(),
		);

		if ( $currency ) {
			$this->data['currency'] = $currency;
		}

		return $this->data;
	}

}
