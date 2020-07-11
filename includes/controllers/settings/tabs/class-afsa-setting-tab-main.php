<?php

class AFSA_Setting_Tab_Main extends AFSA_Setting_Tab {

	public function init() {

		if ( isset( $_GET['account_id'] ) ) {
			$account_id = $_GET['account_id'];

			if ( AFSA_Config::validate_account_id( $account_id ) ) {
				$this->settings['account_id'] = $account_id;
			}
		}

		$account_id = empty( $this->settings['account_id'] ) ?
				'00000000' :
				$this->settings['account_id'];

		$this->add_section(
			'afsa_main_section',
			__( 'Main Settings' ),
			function() {
					// return optional description;
			}
		);

		add_settings_field(
			'account_id',
			__( 'Account ID', 'afsanalytics' ),
			function() use ( $account_id ) {

					print AFSA_Tools::render_js_data(
						array( 'AFSA_site_infos' => AFSA_Account_Manager::get()->get_account_creation_params() )
					);

					$help = AFSA_Config::get_account_id() ?
					__( 'Your AFS Analytics account ID', 'afsanalytics' ) :
					__( 'Enter your AFS Analytics account ID or', 'afsanalytics' )
					. ' <span class="afsa_create_account afsa_link">'
					. __( 'Create a free Account', 'afsanalytics' ) . '</span> '
					. __( 'if you do not have one yet', 'afsanalytics' );

					print '<input type="text"  pattern="[0-9]*" maxlength=8 '
					. $this->input_name( 'account_id' )
					. 'value=' . $account_id . '>'
					. '<p class=afsa_help>'
					. $help
					. '.</p>';
			},
			$this->page,
			$this->section_id
		);

		$this->add_checkboxes(
			array(
				'admin_pages_tracking'     => __( 'Track Admin Page', 'afsanalytics' ),
				'user_logged_tracking'     => __( 'Track user loggin', 'afsanalytics' ),
				'display_admin_summary'    => __( 'Display day trends summary on Admin Dashboard', 'afsanalytics' ),
				'gravatar_profile_enabled' => __( 'Enable gravatar profile support', 'afsanalytics' ),
			)
		);

		add_settings_field(
			'accesskey',
			__( 'Access Key', 'afsanalytics' ),
			function() {

					print '<input type="text"   '
					. $this->input_name( 'accesskey' )
					. 'value=' . (
					empty( $this->settings['accesskey'] ) ?
							'' :
							$this->settings['accesskey']
					)
					. '>'
					. '<p class="afsa_help ">'
					. __( 'An Access key allow you to access your AFS Analytics Dashboard without providing a password each time.', 'afsanalytics' )
					. '</p>'
					. ' <div class="afsa_warpto afsa_access_key" data-to="' . AFSA_Route_Manager::keys() . '">'
					. __( 'Create an access key', 'afsanalytics' ) . '</div> ';
			},
			$this->page,
			$this->section_id
		);

		// access
	}

}
