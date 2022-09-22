<?php

GFForms::include_addon_framework();
use Helpers\OAuth2Helper; 

class GFHubspotAddOn extends GFAddOn {

	protected $_version = GF_HUBSPOT_ADDON_VERSION;
	protected $_min_gravityforms_version = '2.5';
	protected $_slug = 'hubspotaddon';
	protected $_path = 'hubspotaddon/hubspotaddon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Hubspot Add-On Settings';
	protected $_short_title = 'Hubspot Add-On';

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
		add_filter( 'gform_submit_button', array( $this, 'form_submit_button' ), 10, 2 );
		add_action( 'gform_after_submission', array( $this, 'after_submission' ), 10, 2 );
		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );
		// add_filter( 'gform_addon_feed_settings_fields', array( $this, 'gf_hs_addon_field_maps' ), 10, 2 );
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
				'title'  => esc_html__( 'Hubspot Add-On Settings', 'hubspotaddon' ),
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
				$field_map_options[] = array(
					'label' => $field_group->fields[0]->label,
					'value' => $field_group->fields[0]->name
				);
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
	 * Modify the options for field mapping 
	 *
	 * @param array $field The field properties.
	 * @param bool|true $echo Should the setting markup be echoed.
	 */
	public function gf_hs_addon_field_maps( $fields, $form_id, $field_type, $exclude_field_types ) {
	 
			// foreach($fields as $key => $field):
			// 	if($field['name'] == 'hs_field_map'):
			// 		include GF_HUBSPOT_ADDON_DIR . '/lib/actions/getForms.php';
			// 		$settings = $this->get_form_settings( $form_id );
			// 		$hs_form = explode("_", $settings['hs_form']);
			// 		$hs_form_key = $hs_form[0];
			// 		
			// 		$field['field_map'] = array( array ( 'label' => 'Select Hubspot Form Field', 'value' => '' ) );
			// 		
			// 		foreach($forms[$hs_form_key]->formFieldGroups as $field_group):
			// 			// echo "<pre>"; print_r($field_group); echo "</pre>";
			// 			$field['field_map'][] = array(
			// 				'label' => $field_group->fields[0]->label,
			// 				'value' => $field_group->fields[0]->name
			// 			);
			// 		endforeach;
			// 	endif;
			// endforeach;
			print_r($fields); die();
	 
	 		$fields[] = array( 'label' => 'New Choice', 'value' => 'new choice' );
			return $fields;
	}

	/**
	 * Define the markup for the my_custom_field_type type field.
	 *
	 * @param array $field The field properties.
	 * @param bool|true $echo Should the setting markup be echoed.
	 */
	public function settings_my_custom_field_type( $field, $echo = true ) {
		echo '<div>' . esc_html__( 'My custom field contains a few settings:', 'hubspotaddon' ) . '</div>';

		// get the text field settings from the main field and then render the text field
		$text_field = $field['args']['text'];
		$this->settings_text( $text_field );

		// get the checkbox field settings from the main field and then render the checkbox field
		$checkbox_field = $field['args']['checkbox'];
		$this->settings_checkbox( $checkbox_field );
	}

	/**
	 * Performing a custom action at the end of the form submission process.
	 *
	 * @param array $entry The entry currently being processed.
	 * @param array $form The form currently being processed.
	 */
	public function after_submission( $entry, $form ) {

		// Evaluate the rules configured for the custom_logic setting.
		$result = $this->is_custom_logic_met( $form, $entry );

		if ( $result ) {
			// Do something awesome because the rules were met.
		}
	}


	// # HELPERS -------------------------------------------------------------------------------------------------------

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