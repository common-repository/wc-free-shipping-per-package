<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   604-1097 View St                                        */
/* OF         Victoria, BC, V8V 0G9                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Utils;

defined('ABSPATH') || exit; // Exit if accessed directly

if (!class_exists(__NAMESPACE__ . '\\PluginDependency')):

class PluginDependency
{
	protected $id;
	protected $name;
	protected $plugins;
	protected $missingPlugins;

	public function __construct($id, $name)
	{
		$this->id = $id;
		$this->name = $name;
		$this->plugins = array();
		$this->missingPlugins = array();
	}

	public function register()
	{
		add_action('admin_notices', array($this, 'displayNotice'));
	}

	public function add($file, $name, $url)
	{
		$this->missingPlugins = array();
		$this->plugins[$file] = array($name, $url);
	}

	public function validate()
	{
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . '/wp-admin/includes/plugin.php');
		}

		if (empty($this->missingPlugins)) {
			foreach ($this->plugins as $file => $plugin) {
				if (!is_plugin_active($file)) {
					$this->missingPlugins[$file] = $plugin;
				}
			}
		}

		return empty($this->missingPlugins);
	}

	public function displayNotice()
	{
		if ($this->validate()) {
			return;
		}

		$notice = sprintf(
			'<div id="message" class="error"><p><strong>%s</strong> %s</p>', 
			$this->name, 
			__('requires the following plugins to be installed and activated:', $this->id)
		);

		foreach ($this->missingPlugins as $plugin) {
			$notice  .= sprintf(
				'<li><a href="%s" class="thickbox open-plugin-details-modal" target="_blank">%s</a></li>',
				$plugin[1], 
				$plugin[0]
			);
		}

		$notice .= '<p></p></div>';

		echo $notice;
	}
}

endif;