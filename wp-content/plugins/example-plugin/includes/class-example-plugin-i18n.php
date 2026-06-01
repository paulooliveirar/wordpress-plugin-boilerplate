<?php
namespace ExamplePlugin\Includes;

/**
 * Define the internationalization functionality.
 *
 * @since 1.0.0
 */
class ExamplePlugin_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'example-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
