<?php
/**
 * @package ContactFormPro
 * @version 1.2.1
 */
/*
Plugin Name: ContactForm.Pro - Floating Contact Form Widget - No Coding, No Servers & Messages To Your Email
Plugin URI: https://contactform.pro
Description: Get a powerful and easy-to-use floating contact form widget with ContactForm.Pro - no coding or mail servers required.
Author: Cloud Wrestlers Ltd
Version: 1.2.1
*/

function enqueue_contactformpro()
{
	$options = get_option('contactformpro_options');
	if (isset($options['key'])) {
		wp_enqueue_script('contactformpro', 'https://embed.contactform.pro/cf.js', null, null, false);
	}
}
add_action('wp_enqueue_scripts', 'enqueue_contactformpro');

function decorate_contactformpro($tag, $handle, $src)
{
	if ('contactformpro' != $handle) {
		return $tag;
	}
	$options = get_option('contactformpro_options');
	return str_replace('<script', '<script data-cf="' . esc_attr($options['key']) . '" data-cf-origin="wp" async', $tag);
}
add_filter('script_loader_tag', 'decorate_contactformpro', 10, 3);

function load_contactformpro_settings()
{
	?>
	<div
		style="max-width:800px; margin:3em auto; box-shadow:0px 0px 6px 0px rgb(0 0 0 / 15%); padding:2em; background:white; position:relative">
		<div style="position: absolute; right: 0; top: 0; background: #F7B84D; color: white; padding: 0.5em; border-radius: 0 0 0 8px;"
			title="Associate a form key below to enable">
			<?php
			$options = get_option('contactformpro_options');
			echo isset($options['key']) ? '&check; Enabled' : '&#10007; Disabled';
			?>
		</div>
		<img src="<?php echo plugin_dir_url(__FILE__); ?>logo.png" style="width:250px" />
		<p>Thanks for installing the ContactForm.Pro plugin! Are you ready to add a contact widget to WordPress in record
			time?</p>
		<h2>Get started in three easy steps</h2>
		<ol class="ui list">
			<li>Sign up for a free account at <a href="https://app.contactform.pro" target="_blank">app.contactform.pro</a>.
			</li>
			<li>Create a new form and copy the key from the installation tab.</li>
			<li>Paste the key in the <b>Form key</b> field below and click save.</li>
		</ol>
		<hr />
		<form action="options.php" method="post">
			<?php
			settings_fields('contactformpro_options');
			do_settings_sections('contactformpro'); ?>
			<!-- <input name="submit" class="button button-primary" type="submit" value="Save" /> -->
		</form>
		<hr />
		<h2>Help</h2>
		<p>Need a hand? Get in touch via our <a target="_blank" href="https://contactform.pro/">website</a> or take a look
			at our <a target="_blank" href="https://contactform.pro/help/">guides</a>.</p>
		<hr />
		<div style="color:#a7a7a7; text-align:center; margin-top:1em;">
			&copy; Cloud Wrestlers Ltd&nbsp;&bull;&nbsp;
			<a target="_blank" href="https://app.contactform.pro/" style="text-decoration:none; color:#a7a7a7;">App</a>
			&nbsp;&bull;&nbsp;
			<a target="_blank" href="https://contactform.pro/privacy/"
				style="text-decoration:none; color:#a7a7a7;">Privacy</a>&nbsp;&bull;&nbsp;
			<a target="_blank" href="https://contactform.pro/terms/"
				style="text-decoration:none; color:#a7a7a7;">Terms</a>&nbsp;&bull;&nbsp;
			<a target="_blank" href="https://contactform.pro/help/" style="text-decoration:none; color:#a7a7a7;">Help</a>
		</div>
	</div>
	<?php
}


function create_contactformpro_settings_page()
{
	add_options_page('ContactForm.Pro Settings', 'ContactForm.Pro', 'manage_options', 'contactformpro', 'load_contactformpro_settings');
}
add_action('admin_menu', 'create_contactformpro_settings_page');


function contactformpro_options_validate($input)
{
	$newinput['key'] = trim($input['key']);
	if (!preg_match('/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/i', $newinput['key'])) {
		$newinput['key'] = '';
	}

	return $newinput;
}


function contactformpro_register_settings()
{
	register_setting('contactformpro_options', 'contactformpro_options', 'contactformpro_options_validate');
	add_settings_section('settings', 'Settings', 'render_contactformpro_settings_text', 'contactformpro');
	add_settings_field('contactformpro_setting_key', 'Form key', 'contactformpro_setting_key', 'contactformpro', 'settings');
}
add_action('admin_init', 'contactformpro_register_settings');

function render_contactformpro_settings_text()
{
	$url = get_site_url();
	$options = get_option('contactformpro_options');

	if (isset($options['key'])) {
		echo '<p><b>Success!</b> ContactForm.Pro is now enabled on your WordPress <a href="' . esc_url($url) . '" target="_blank">site</a>.</p><p>You can manage your widget settings at <a href="https://app.contactform.pro" target="_blank">app.contactform.pro</a>.</p>';
	} else {
		echo '';
	}

}

function contactformpro_setting_key()
{
	$options = get_option('contactformpro_options');
	$url = '';
	if (isset($options['key'])) {
		$url = $options['key'];
	}
	echo "<input id='contactformpro_setting_key' name='contactformpro_options[key]' type='text' value='" . esc_attr($url) . "' style='min-width: 25em; margin-right: 1em;' placeholder='5c038581-114c-49d8-9375-5b682bc9e193' /><input name='submit' class='button button-primary' type='submit' value='Save' />";
}

function contactformpro_settings_link(array $links)
{
	$url = get_admin_url() . "options-general.php?page=contactformpro";
	$settings_link = '<a href="' . esc_url($url) . '">Settings</a>';
	$links[] = $settings_link;
	return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'contactformpro_settings_link');

?>