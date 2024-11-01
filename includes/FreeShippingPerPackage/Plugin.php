<?php
/*********************************************************************/
/*  PROGRAM          FlexRC                                          */
/*  PROPERTY         604-1097 View St                                 */
/*  OF               Victoria BC   V8V 0G9                          */
/*  				 Voice 604 800-7879                              */
/*                                                                   */
/*  Any usage / copying / extension or modification without          */
/*  prior authorization is prohibited                                */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\FreeShippingPerPackage;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\Plugin')):

class Plugin
{
	public static $pluginId;
	protected $id;
	protected $mainMenuId;
	protected $pluginPath;
	protected $optionKey;
	protected $settings;

    public function __construct($pluginPath)
    {
		self::$pluginId = $this->id = str_replace('-pro', '', basename($pluginPath, '.php')) . '-pro';
		$this->mainMenuId = 'oneteamsoftware';
		$this->pluginPath = $pluginPath;

		$this->optionKey = '';
		$this->settings = array(
			'enabled' => 'no',
			'licenseKey' => '',
		);
	}

    public function register()
    {
		if (!function_exists('is_plugin_active')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}

		$proPluginName = preg_replace('/(\.php|\/)/i', '-pro\\1', plugin_basename($this->pluginPath));
		if (is_plugin_active($proPluginName)) {
			return false;
		}

		$pluginDependency = new \OneTeamSoftware\WooCommerce\Utils\PluginDependency($this->id, __('Free Shipping Per Package', $this->id));
		$pluginDependency->add('woocommerce/woocommerce.php', __('WooCommerce', $this->id), admin_url('/plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=600&height=550'));
		$pluginDependency->register();

		if (!$pluginDependency->validate()) {
			return false;
		}

        // The settings are needed only when in the admin area.
        if (is_admin()) {
			add_action('woocommerce_settings_saved', array($this, 'onSettingsSaved'));
			add_filter('plugin_action_links_' . plugin_basename($this->pluginPath), array($this, 'onPluginActionLinks'), 1, 1);

			\OneTeamSoftware\WooCommerce\Admin\OneTeamSoftware::instance()->register();
			
			add_action('admin_menu', array($this, 'onAdminMenu'));
			add_filter($this->id . '_form_fields', array($this, 'addProFeatureAttributes'), PHP_INT_MAX, 1);
			add_filter($this->id . '_instance_form_fields', array($this, 'addProFeatureAttributes'), PHP_INT_MAX, 1);
		}
		
		add_filter('woocommerce_shipping_methods', array($this, 'addShippingMethod'));
		
		add_action('init', array($this, 'onInit'), 1);

		return true;
    }

	public function onPluginActionLinks($links)
	{
		$link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=wc-settings&tab=shipping&section=wc-free-shipping-per-package-pro'), __('Settings', $this->id));
		array_unshift($links, $link);
		
		return $links;
	}

	public function onAdminMenu()
	{
		add_submenu_page($this->mainMenuId, __('Free Shipping Per Package', $this->id), __('Free Shipping Per Package', $this->id), 'manage_options', 'admin.php?page=wc-settings&tab=shipping&section=wc-free-shipping-per-package-pro');
	}
	
	public function addProFeatureAttributes(array $fields)
	{
		$proFeatureSuffix = sprintf(' <strong>(%s <a href="%s" target="_blank">%s</a>)</strong>', 
			__('Requires', $this->id), 
			'https://1teamsoftware.com/product/' . preg_replace('/wc/', 'woocommerce', $this->id) . '-pro/',
			__('PRO Version', $this->id)
		);

		$proFeatureAttributes = array(
			'disabled' => 'yes'
		);

		foreach ($fields as $key => $field) {
			if (!empty($field['proFeature'])) {
				if (isset($field['options'])) {
					if (!isset($field['description'])) {
						$field['description'] = '';
					}
				}

				if (isset($field['label'])) {
					$field['label'] .= $proFeatureSuffix;
				} else if (isset($field['description'])) {
					$field['description'] .= $proFeatureSuffix;
				} else if (isset($field['title'])) {
					$field['title'] .= $proFeatureSuffix;
				}

				if (empty($field['custom_attributes'])) {
					$field['custom_attributes'] = array();
				}

				$field['custom_attributes'] = array_merge($field['custom_attributes'], $proFeatureAttributes);

				$fields[$key] = $field;
			}
		}

		return $fields;
	}

	protected function registerConditions()
	{
		(new Condition\FreeShippingCoupon($this->id))->register();
		(new Condition\Vendor($this->id))->register();
		(new Condition\UserRole($this->id))->register();
		(new Condition\Category($this->id))->register();
		(new Condition\ShippingClass($this->id))->register();
		(new Condition\Tag($this->id))->register();
		(new Condition\Taxonomy($this->id))->register();
		(new Condition\Quantity($this->id))->register();
		(new Condition\Currency($this->id))->register();
		(new Condition\Price($this->id))->register();
		(new Condition\Weight($this->id))->register();
		(new Condition\Volume($this->id))->register();
		(new Condition\DayOfTheWeek($this->id))->register();
		(new Condition\Date($this->id))->register();
		(new Condition\Time($this->id))->register();
	}

	public function onSettingsSaved()
	{
		$this->loadSettings();
	}

	public function onInit()
	{
		$this->loadSettings();
		$this->registerConditions();
	}
	
	public function addShippingMethod($methods)
	{
		$methods[$this->id] = $this->getShippingMethodClassName();

		return $methods;
	}

	protected function getShippingMethodClassName()
	{
		return __NAMESPACE__ . '\\ShippingMethod';
	}

	protected function loadSettings()
	{
		$className = $this->getShippingMethodClassName();
		
		$this->optionKey = (new $className())->get_option_key();
		$this->settings = array_merge($this->settings, get_option($this->optionKey, array()));
	}

}

endif;
