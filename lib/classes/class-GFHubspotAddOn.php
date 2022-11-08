<?php

GFForms::include_addon_framework();
use Helpers\OAuth2Helper; 

class GFHubspotAddOn extends GFAddOn {

	protected $_version = GF_HUBSPOT_ADDON_VERSION;
	protected $_min_gravityforms_version = '2.5';
	protected $_slug = 'hubspotaddon';
	protected $_path = 'hubspotaddon/hubspotaddon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Hubspot Form Submission Settings';
	protected $_short_title = 'Hubspot Form Submission';

	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFHubspotAddOn
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFHubspotAddOn();
		}

		return self::$_instance;
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {
		parent::init();
		add_action( 'gform_after_submission', array( $this, 'after_submission' ), 10, 2 );
		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );
		add_action( 'gform_field_map_choices', array( $this, 'gf_hs_addon_field_maps' ), 10, 4 );
	}
	
	
	// # REST API FUNCTIONS ---------------------------------------------------------------------------------------------
	
	
	/**
	 * Register API Endpoints
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function register_api_endpoints() {
	
		register_rest_route( 'gf-hs/v1', '/auth/',
			array(
				'methods'  => 'GET',
				'callback' => [ $this, 'gfhs_store_hubspot_auth' ]
			)
		);
		
	}
	
	/**
	 * Creates a custom page for this add-on.
	 */
	public function gfhs_store_hubspot_auth( $request ){
		
		include GF_HUBSPOT_ADDON_DIR . '/lib/actions/callback.php';
		
	}


	// # ADMIN FUNCTIONS -----------------------------------------------------------------------------------------------
	
	/**
	 * Creates a custom page for this add-on.
	 */
	public function plugin_page() {
		include GF_HUBSPOT_ADDON_DIR . '/lib/views/admin-settings-page.php';
	}
	
	/**
	 * Configures the settings which should be rendered on the add-on settings tab.
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				'title'  => esc_html__( 'Hubspot Form Submission Settings', 'hubspotaddon' ),
				'fields' => array(
					array(
						'name'              => 'mytextbox',
						'tooltip'           => esc_html__( 'This is the tooltip', 'hubspotaddon' ),
						'label'             => esc_html__( 'This is the label', 'hubspotaddon' ),
						'type'              => 'text',
						'class'             => 'small',
						'feedback_callback' => array( $this, 'is_valid_setting' ),
					)
				)
			)
		);
	}

	/**
	 * Configures the settings which should be rendered on the Form Settings > Hubspot Add-On tab.
	 *
	 * @return array
	 */
	public function form_settings_fields( $form ) {
		include GF_HUBSPOT_ADDON_DIR . '/lib/actions/getForms.php';
		$settings = $this->get_form_settings( $form );
		
		$form_options = array( array( 'label' => 'Select a Hubspot Form', 'value' => '' ) );
		foreach($forms as $key => $form):
			$form_options[] = array(
				'label' => $form->name,
				'value' => $key . '_' . $form->guid
			);
		endforeach;
				
		$field_map_options = array( array ( 'label' => 'Select Hubspot Form Field', 'value' => '' ) );
		if(!empty($settings['hs_form'])):
			$hs_form = explode("_", $settings['hs_form']);
			$hs_form_key = $hs_form[0];
			
			foreach($forms[$hs_form_key]->formFieldGroups as $field_group):
				// echo "<pre>"; print_r($field_group); echo "</pre>";
				foreach($field_group->fields as $field):
					$field_map_options[] = array(
						'label' => $field->label,
						'value' => $field->name
					);
				endforeach;
			endforeach;
			// echo "<pre>"; print_r($forms[$hs_form_key]); echo "</pre>";
		endif;
		
		return array(
			array(
				'title'  => esc_html__( 'Hubspot Form Settings', 'hubspotaddon' ),
				'fields' => array(
					array(
						'label'   => esc_html__( 'Hubspot Form', 'hubspotaddon' ),
						'type'    => 'select',
						'name'    => 'hs_form',
						'tooltip' => esc_html__( 'Select the Hubspot form you would like to push submissions to this form to.', 'hubspotaddon' ),
						'choices' => $form_options
					),
					array(
						'label' => esc_html__( 'Field Mapping', 'hubspotaddon' ),
						'type'  => 'dynamic_field_map',
						'name'  => 'hs_field_map',
						'tooltip' => esc_html__( 'Select a field from the Hubspot form as the Key and select the matching field from the Gravity Form as the Value.', 'hubspotaddon' ),
						'field_map' => $field_map_options,
						'enable_custom_key' => false
					),
				),
			),
		);
	}

	/**
	 * Performing a custom action at the end of the form submission process.
	 *
	 * @param array $entry The entry currently being processed.
	 * @param array $form The form currently being processed.
	 */
	public function after_submission( $entry, $form ) {

		// create the form field mappings for hubspot 
		$hs_data_json = $this->prepare_hubspot_submission_data($form['hubspotaddon'], $entry);
		
		// send hs data to hubspot form submission 
		$result = $this->send_submission_to_hubspot( $form['hubspotaddon']['hs_form'], $hs_data_json );

		if ( $result->status && $result->status == "error" ) {
			// Do something awesome because the rules were met.
			// $myfile = fopen( GF_HUBSPOT_ADDON_DIR . "/logs/hubspot-error-logs.txt", "a" ) or die( "Unable to open file!" );
			$logFile = GF_HUBSPOT_ADDON_DIR . "/logs/hubspot-error-logs.txt";
			foreach($result->errors as $error):
				$txt = '['.date( 'm-d-Y h:i:sa' ).'] - GF Form ID: '.$form['id'].' | Hubspot Error: '.$error->errorType.' | '.$error->message."\n";
			endforeach;
			$currentLog = file_get_contents($logFile);
			file_put_contents($logFile, $txt.$currentLog);
			// fwrite($myfile, $txt);
			// fclose($myfile);
		}
	}


	// # HELPERS -------------------------------------------------------------------------------------------------------
		
	/**
	 * Take the field mapping from the settings of the form and build the Hubspot form data array of the values
	 *
	 * @param array $hubspot_settings The settings array from the form object.
	 * @param array $entry The entry array from the form submission.
	 *
	 * @return json object
	 */
	public function prepare_hubspot_submission_data( $hubspot_settings, $entry ){
		
		$hubspotutk		= $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
		$ip_addr			= $_SERVER['REMOTE_ADDR']; //IP address too.
		$page_uri			= "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$extra_fields = array();
		
		$hs_data = array(
			"fields" => array(),
			"context" => array(
				"hutk" => $hubspotutk,
				"ipAddress" => $ip_addr,
				"pageUri" => $page_uri,
				"pageName" => get_the_title()
			)
		);
		
		foreach($hubspot_settings['hs_field_map'] as $hs_field_map):
			$hs_data['fields'][] = array(
				"name" => $hs_field_map['key'],
				"value" => $entry[$hs_field_map['value']]
			);
		endforeach;
		
		return $hs_data;
		// return json_encode($hs_data);
		
	}
	
	/**
	 * Take the field mapping from the settings of the form and build the Hubspot form data array of the values
	 *
	 * @param array $hubspot_settings The settings array from the form object.
	 * @param array $entry The entry array from the form submission.
	 *
	 * @return json object
	 */
	public function send_submission_to_hubspot( $hubspot_form, $hs_data_json ){
		
		include GF_HUBSPOT_ADDON_DIR . '/lib/actions/getAccount.php';
		// $access_token = OAuth2Helper::getAccessToken();
		$hs_form_id = substr($hubspot_form, 1, 1) == "_" ? substr($hubspot_form, 2) : $hubspot_form;
		// $endpoint = "https://api.hsforms.com/submissions/v3/integration/secure/submit/".$portal_id."/".$hs_form_id;
		
		include GF_HUBSPOT_ADDON_DIR . '/lib/actions/submitForm.php';
		
		// $ch = @curl_init();
		// @curl_setopt($ch, CURLOPT_POST, true);
		// @curl_setopt($ch, CURLOPT_POSTFIELDS, $hs_data_json);
		// @curl_setopt($ch, CURLOPT_URL, $endpoint);
		// @curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		// 	'Authorization: Bearer ' . $access_token,
		// 	'Content-Type: application/json'
		// ));
		// @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $response    = @curl_exec($ch); //Log the response from HubSpot as needed.
		// $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); //Log the response status code
		// @curl_close($ch);
		
		// echo "<pre>"; print_r($submit_response); die();		
		return $submit_response;
		
	}

	/**
	 * The feedback callback for the 'mytextbox' setting on the plugin settings page and the 'mytext' setting on the form settings page.
	 *
	 * @param string $value The setting value.
	 *
	 * @return bool
	 */
	public function is_valid_setting( $value ) {
		return strlen( $value ) < 10;
	}

}