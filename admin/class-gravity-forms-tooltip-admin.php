<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.facebook.com/disismehbub
 * @since      1.0.0
 *
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gravity_Forms_Tooltip
 * @subpackage Gravity_Forms_Tooltip/admin
 * @author     Mehbub Rashid <rashidiam1998@gmail.com>
 */
class Gravity_Forms_Tooltip_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gravity_Forms_Tooltip_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gravity_Forms_Tooltip_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gravity-forms-tooltip-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gravity_Forms_Tooltip_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gravity_Forms_Tooltip_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gravity-forms-tooltip-admin.js', array( 'jquery' ), $this->version, false );

	}

	function tooltip_input( $position, $form_id ) {
 
		//create settings on position 25 (right after Field Label)
		if ( $position == 25 ) {
			?>
			<li class="tooltip_input field_setting" style="display:list-item !important">
				<label for="tooltip_input" class="section_label">
					<?php esc_html_e( 'Tooltip Text', 'gravityforms' ); ?>
				</label>
				<input type="text" id="tooltip_input" onchange="SetFieldProperty('tooltiptext', this.value);" /> 
			</li>
			<?php
		}
	}

	function tooltip_editor_script(){
		?>
		<script type='text/javascript'>
			//adding setting to fields of type "text"
			fieldSettings.text += ', tooltip_input';
	 
			//binding to the load field settings event to initialize the values
			jQuery(document).on('gform_load_field_settings', function(event, field, form){
				jQuery('#tooltip_input').val(field['tooltiptext']);
				jQuery('.tooltip_input.field_setting').show();
			});
		</script>
		<?php
	}

	
	function render_tooltips( $content, $field, $value, $lead_id, $form_id ) {
		if(strlen($field->tooltiptext) > 0) {
			$icon_html = "<div class='gravity-tooltip'></div>";
		
			//Wrap the icon markup inside tooltip markup
			$icon_html = "<span class=\"advanced-tooltip\" tooltip=\"$field->tooltiptext\" flow=\"right\">".$icon_html."</span>";

			//Get the label markup
			preg_match('/<label.*gfield_label.+?(?=<div)/i', $content, $label_markup);
			if(count($label_markup) > 0) {
				$label_markup = $label_markup[0];
			}
			else {
				$label_markup = '';
			}
			

			//Append icon after label markup
			$label_markup .= $icon_html;

			//Replace the old label markup with the new one
			$content = preg_replace('/<label.*gfield_label.+?(?=<div)/i', $label_markup, $content);
		}
		
		return $content;
		// return str_replace( "class='gfield_label'", "class='gfield_label' data-tooltiptext='".$field->tooltiptext."'", $content );
	}

	function tooltip_update_checker() {
		if( get_transient( 'tooltip_update_checker' ) ){
			?>
			<div class="error is-dismissible"><p><?php echo __( 'New version of <strong>Tooltip for Gravity Forms is available!</strong> Update now to get new features', 'tooltip-for-gravity-forms' ); ?></p></div>
			<?php
		}
	}

	function show_gravitizer_notice(){
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 
		
		if(get_option('display_gravitizer_notice', 'yes') == 'yes'){
			// Check if not currently any of gravitizer installed
			if(!is_plugin_active( 'gravitizer-lite/gravitizer-lite.php') && !is_plugin_active( 'gravitizer/gravitizer.php')){

				// Check if was previously installed any time
				if(get_option('maybe_gravitizer_installed', 'no') == 'no'){
					?>
					<div class="grav-notice notice notice-success"> 
						<div class="gravitizer-gif" style="text-align: center;">
							<a target="_blank" href="<?php echo admin_url('plugin-install.php?s=gravitizer&tab=search&type=term'); ?>">
								<img style="width: 60%;" src="<?php echo plugin_dir_url( __FILE__ ) . 'images/gravitizer-beforeafter.gif'; ?>" alt="">
							</a>
						</div>
						<div class="notice-buttons">
							<a target="_blank" href="https://wordpress.org/plugins/gravitizer-lite"><button class="button button-primary"><?php  esc_html_e('Plugin Repository', 'tooltip-for-gravity-forms'); ?></button></a>
							<a target="_blank" href="<?php echo admin_url('plugin-install.php?s=gravitizer&tab=search&type=term'); ?>"><button class="button button-primary"><?php  esc_html_e('Install Now', 'tooltip-for-gravity-forms'); ?></button></a>
							<a href="?grav-dismiss=true"><button class="button button-cancel"><?php  esc_html_e('Close this', 'tooltip-for-gravity-forms'); ?></button></a>
						</div>
					</div>
					<?php
				}
			}
			
		}
	}



	function set_updater_transient( $data, $response ) {
		if( isset( $data['update'] ) ) {
			set_transient( 'tooltip_update_checker', true);
		}
		else {
			delete_transient( 'tooltip_update_checker' );
		}
	}

	function detect_plugin_update() {
		if (get_option( 'tooltip_plugin_version' ) != GRAVITY_FORMS_TOOLTIP_VERSION) {
			//Plugin has been updated

			
			if(!isset(get_option('gravity_tooltip_options')['allow_update'])) {
				$toset = array(
					'allow_update' => '1'
				);
				update_option('gravity_tooltip_options', $toset);
			}

			delete_transient( 'tooltip_update_checker' );
			update_option('tooltip_plugin_version', GRAVITY_FORMS_TOOLTIP_VERSION);
		}

		// Opting for email update
		$data = get_option('admin_email');
		if(get_option('tooltip_news_update_sent') != 'sent') {
			if(isset(get_option('gravity_tooltip_options')['allow_update'])) {
				$to = 'divdojo@gmail.com';
				$subject = "I want to get notified via email";
				$message = $data;

				$sent = wp_mail($to, $subject, strip_tags($message));
				if($sent) {
					update_option('tooltip_news_update_sent', 'sent');
				}
				else {
					update_option('tooltip_news_update_sent', 'notsent');
				}
			}
		}
	}

	function auto_update_this_plugin ( $update, $item ) {
		// Array of plugin slugs to always auto-update
		$plugins = array (
			'tooltip-for-gravity-forms'
		);
		if ( in_array( $item->slug, $plugins ) ) {
			return true;
		} else {
			return $update;
		}
	}

	public function tooltip_add_settings_page() {
		add_options_page( 'Tooltip Settings', 'Tooltips', 'manage_options', 'gravity-tooltip-settings', array($this, 'tooltip_render_plugin_settings_page') );
	}

	public function tooltip_render_plugin_settings_page() {
		?>
		<form action="options.php" method="post">
			<?php 
			settings_fields( 'gravity_tooltip_options' );
			do_settings_sections( 'gravity_tooltip_section' ); ?>
			<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
		</form>
		<?php
	}
	public function tooltip_register_settings() {
		register_setting( 'gravity_tooltip_options', 'gravity_tooltip_options', 'gravity_tooltip_options_validate' );
		add_settings_section( 'update_settings', 'Tooltip Settings', array($this, 'tooltip_update_section_text'), 'gravity_tooltip_section' );
	
		add_settings_field( 'gravity_tooltip_setting_inform_update', 'Inform me about updated news via email', array($this, 'gravity_tooltip_setting_inform_update'), 'gravity_tooltip_section', 'update_settings' );

		// if notice dismiss is found on url, update the notice display option
		if(isset($_GET['grav-dismiss'])){
			update_option('display_gravitizer_notice', 'no');
		}
	}
	function tooltip_update_section_text() {
		echo '';
	}
	public function gravity_tooltip_setting_inform_update() {
		$options = get_option( 'gravity_tooltip_options' );
		$html = '';
		if(isset($options['allow_update'])) {
			$html = '<input type="checkbox" id="gravity_tooltip_setting_inform_update" name="gravity_tooltip_options[allow_update]" value="1"' . checked( 1, $options['allow_update'], false ) . '/>';
			$html .= '<label for="gravity_tooltip_setting_inform_update">Yes</label>';
		}
		else {
			$html = '<input type="checkbox" id="gravity_tooltip_setting_inform_update" name="gravity_tooltip_options[allow_update]" value="1"/>';
			$html .= '<label for="gravity_tooltip_setting_inform_update">Yes</label>';
		}

		echo $html;
	}
	
}
