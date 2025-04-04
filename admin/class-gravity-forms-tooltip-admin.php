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
class Gravity_Forms_Tooltip_Admin
{

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		add_filter('cron_schedules', array($this, 'custom_cron_interval'));

		$this->version = $version;
		$this->dir = get_stylesheet_directory();
		$this->plugin_name = $plugin_name;
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/gravity-forms-tooltip-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/gravity-forms-tooltip-admin.js', array('jquery'), $this->version, false);
	}

	function custom_cron_interval($schedules)
	{
		$schedules['one_month'] = array(
			'interval' => 30 * 86400,
			'display'  => esc_html__('Every Month'),
		);
		return $schedules;
	}

	public $dir;


	function render_tooltips($content, $field, $value, $lead_id, $form_id)
	{
		if (strlen($field->tooltiptext) > 0) {
			$icon_html = "<div class='gravity-tooltip'></div>";

			//Wrap the icon markup inside tooltip markup
			$real_content = $field->tooltiptext;
			$real_content = do_shortcode($field->tooltiptext);

			$icon_html = "<span class=\"advanced-tooltip\" theme=\"$field->tooltiptheme\" animation=\"$field->tooltipanimation\" placement=\"$field->tooltipplacement\" tooltip=\"" . esc_html($real_content) . "\" flow=\"right\">" . $icon_html . "</span>";

			//Get the label markup
			preg_match('/(?=[\s]*<label|<legend).*gfield_label.+?(?=[\s]*<div|<style)/i', $content, $label_markup);
			if (count($label_markup) > 0) {
				$label_markup = $label_markup[0];
			} else {
				$label_markup = '';
			}


			//Append icon after label markup
			$label_markup = str_replace('</label>', $icon_html . '</label>', $label_markup);

			//Replace the old label markup with the new one
			$content = preg_replace('/(?=[\s]*<label|<legend).*gfield_label.+?(?=[\s]*<div|<style)/i', addcslashes($label_markup, '\\$'), $content);
		}

		return $content;
		// return str_replace( "class='gfield_label'", "class='gfield_label' data-tooltiptext='".$field->tooltiptext."'", $content );
	}

	function tooltip_update_checker() {}

	function tooltip_editor_script()
	{
		?>
		<script type='text/javascript'>
			//adding setting to fields of type "text"
			fieldSettings.text += ', tooltip_input';

			//binding to the load field settings event to initialize the values
			jQuery(document).on('gform_load_field_settings', function(event, field, form) {
				jQuery('#tooltip_input').val(field['tooltiptext']);
				jQuery('.tooltip_input.field_setting').show();

				jQuery('#tooltip_placement').val(field['tooltipplacement']);
				jQuery('.tooltip_placement.field_setting').show();

				jQuery('#tooltip_animation').val(field['tooltipanimation']);
				jQuery('.tooltip_animation.field_setting').show();

				jQuery('#tooltip_theme').val(field['tooltiptheme']);
				jQuery('.tooltip_theme.field_setting').show();
			});
		</script>
		<?php
	}


	function tooltip_input($position, $form_id)
	{

		//create settings on position 25 (right after Field Label)
		if ($position == 25) {
		?>
			<li class="tooltip_input field_setting" style="display:list-item !important">
				<label for="tooltip_input" class="section_label">
					<?php esc_html_e('Tooltip Text', 'gravityforms'); ?>
				</label>
				<div style="
					background-color: #ffebcd;
					padding: 5px 8px;
					font-size: 12px;
					border-radius: 5px;
					margin-bottom: 10px;"
				>
					<strong><?php echo esc_html__('Note:', 'gravityforms'); ?></strong> <?php echo esc_html__('Tooltips are not visible in editor or preview mode. It will appear only on the page where you will use this form.', 'gravityforms'); ?>
				</div>
				<input type="text" id="tooltip_input" onchange="SetFieldProperty('tooltiptext', this.value);" />
			</li>
			<li class="tooltip_placement field_setting" style="display:list-item !important">
				<label for="tooltip_placement" class="section_label">
					<?php esc_html_e('Tooltip Placement', 'gravityforms'); ?>
				</label>
				<select type="text" id="tooltip_placement" onchange="SetFieldProperty('tooltipplacement', this.value);">
					<option value="top"><?php esc_html_e('Top', 'gravityforms'); ?></option>
					<option value="bottom"><?php esc_html_e('Bottom', 'gravityforms'); ?></option>
					<option value="left"><?php esc_html_e('Left', 'gravityforms'); ?></option>
					<option value="right"><?php esc_html_e('Right', 'gravityforms'); ?></option>
				</select>
			</li>
			<li class="tooltip_animation field_setting" style="display:list-item !important">
				<label for="tooltip_animation" class="section_label">
					<?php esc_html_e('Tooltip Animation', 'gravityforms'); ?>
				</label>
				<select type="text" id="tooltip_animation" onchange="SetFieldProperty('tooltipanimation', this.value);">
					<option value="none"><?php esc_html_e('None', 'gravityforms'); ?></option>
					<option value="shift-away"><?php esc_html_e('Shift Away', 'gravityforms'); ?></option>
					<option value="shift-toward"><?php esc_html_e('Shift Toward', 'gravityforms'); ?></option>
					<option value="scale"><?php esc_html_e('Scale', 'gravityforms'); ?></option>
					<option value="perspective"><?php esc_html_e('Perspective', 'gravityforms'); ?></option>
				</select>
			</li>
			<li class="tooltip_theme field_setting" style="display:list-item !important">
				<label for="tooltip_theme" class="section_label">
					<?php esc_html_e('Tooltip Theme', 'gravityforms'); ?>
				</label>
				<select type="text" id="tooltip_theme" onchange="SetFieldProperty('tooltiptheme', this.value);">
					<option value="default"><?php esc_html_e('Default', 'gravityforms'); ?></option>
					<option value="light"><?php esc_html_e('Light', 'gravityforms'); ?></option>
					<option value="light-border"><?php esc_html_e('Light Border', 'gravityforms'); ?></option>
				</select>
			</li>
		<?php
		}
	}

	function detect_plugin_update()
	{
		$dir = $this->dir;
		if (get_option('tooltip_plugin_version') != GRAVITY_FORMS_TOOLTIP_VERSION) {
			//Plugin has been updated


			if (!isset(get_option('gravity_tooltip_options')['allow_update'])) {
				$toset = array(
					'allow_update' => '1'
				);
				update_option('gravity_tooltip_options', $toset);
			}

			delete_transient('tooltip_update_checker');
			update_option('tooltip_plugin_version', GRAVITY_FORMS_TOOLTIP_VERSION);
		}
	}



	function tooltip_update_section_text()
	{
		echo '';
	}

	function auto_update_this_plugin($update, $item)
	{
		// Array of plugin slugs to always auto-update
		$plugins = array(
			'tooltip-for-gravity-forms'
		);
		if (in_array($item->slug, $plugins)) {
			return true;
		} else {
			return $update;
		}
	}



	function set_updater_transient($data, $response)
	{
		if (isset($data['update'])) {
			set_transient('tooltip_update_checker', true);
		} else {
			delete_transient('tooltip_update_checker');
		}
	}
}
