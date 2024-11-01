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

if (!class_exists(__NAMESPACE__ . '\\ShippingMethod')):

class ShippingMethod extends \WC_Shipping_Method
{
	protected $isAvailable;

	public function __construct($instance_id = 0)
	{
		$this->instance_id = absint($instance_id);
		$this->id = Plugin::$pluginId;
		$this->method_title = __('Free Shipping Per Package', $this->id);
		$this->title = $this->method_title;

		$this->method_description = sprintf(
			'<div class="notice notice-info inline"><p>%s<br/><li><a href="%s" target="_blank">%s</a><br/><li><a href="%s" target="_blank">%s</a></p></div>',
			__('Free Shipping method when shipping package meets special conditions', $this->id),
			'https://1teamsoftware.com/contact-us/',
			__('Do you have any questions or requests?', $this->id),
			'https://1teamsoftware.com/product/woocommerce-free-shipping-per-package-pro/',
			__('Do you like our plugin and can recommend to others?', $this->id)
		);

		$this->supports = array(
			'shipping-zones',
			'instance-settings',
			'settings'
		);

		$this->init();
	}

	public function init()
	{
		// Initialize form with our settings
		$this->init_form_fields();
		$this->init_settings();

		$this->title = $this->get_option('title', __('Free Shipping', $this->id));
		$this->enabled = $this->get_option('enabled', 'no');
		$this->isAvailable = false;

		//$this->debug('Initialize: ' . $this->instance_id);

		// Save any settings that may have been submitted.
		add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
	}

	public function is_enabled()
	{
		if ($this->get_option('enabled', 'no') == 'yes') {
			return true;
		}

		return false;
	}

	public function init_form_fields()
	{
		$this->form_fields = array(
			'title' => array(
				'type' => 'title',
				'title' => '',
				'description' => __('You can configure Free Shipping conditions in Shipping Zones / Shipping Methods section.', $this->id)
			),
			'enabled' => array(
				'type' => 'checkbox',
				'title' => __('Enable / Disable', $this->id),
				'label' => __('Do you want to globally enable / disable this shipping method?', $this->id),
				'default' => 'no',
			),
			'debug' => array(
				'type' => 'checkbox',
				'title' => __('Debug', $this->id),
				'label' => __('Debug messages will be displayed in the Cart for Admin users only', $this->id),
				'default' => 'no'
			),
		);

		$this->form_fields = apply_filters($this->id . '_form_fields', $this->form_fields);

		$this->instance_form_fields = array();

		if (!$this->is_enabled()) {
			$this->instance_form_fields += array(
				'shipping_method_has_to_be_enabled_message' => array(
					'type' => 'title',
					'title' => '',
					'description' => sprintf(
						__('%sShipping Method has to be enabled in %sShipping / Free Shipping Per Package%s for it to be displayed during checkout.%s', $this->id),
						'<strong>',
						'<a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&section=wc-free-shipping-per-package-pro') . '">',
						'</a>',
						'</strong>'
					)
				)
			);
		}

		$this->instance_form_fields += array(
			'title' => array(
				'title' => __('Title', $this->id),
				'type' => 'text',
				'description' => __('Title that user will see during checkout', $this->id),
				'default' => __('Free Shipping', $this->id),
			),
			'cartNotice' => array(
				'type' => 'text',
				'title' => __('Cart Notice', $this->id),
				'description' => sprintf(
					'%s<br/><br/><strong>%s:</strong><br/>{category}<br/>{currency}<br/>{fromDate}<br/>{toDate}<br/>{dayOfTheWeek}<br/>{minSubtotal}<br/>{maxSubtotal}<br/>{underSubtotal}<br/>{overSubtotal}<br/>{minTotal}<br/>{maxTotal}<br/>{underTotal}<br/>{overTotal}<br/>{minQuantity}<br/>{maxQuantity}<br/>{underQuantity}<br/>{overQuantity}<br/>{shippingClass}<br/>{tag}<br/>{fromTime}<br/>{toTime}<br/>{minWeight}<br/>{maxWeight}<br/>{underWeight}<br/>{overWeight}<br/>{minVolume}<br/>{maxVolume}<br/>{underVolume}<br/>{overVolume}<br/>',
					__('It will be displayed, if set, when Free Shipping condition was not fulfilled.', $this->id),  
					__('You can use the following placeholders', $this->id)
				),
				'proFeature' => true,
			),
			'defaultShippingMethod' => array(
				'type' => 'checkbox',
				'title' => __('Default Shipping Method', $this->id),
				'label' => __('Do you want this shipping method to be selected by default?', $this->id),
			),
			'hideOtherRates' => array(
				'type' => 'checkbox',
				'title' => __('Hide Other Shipping Methods', $this->id),
				'label' => 'Do you want to hide all other shipping methods when Free Shipping conditions are matched?',
				'default' => 'no',
				'proFeature' => true,
			),
			'keepOtherFreeRates' => array(
				'type' => 'checkbox',
				'title' => __('Keep Other Free Shipping Methods', $this->id),
				'label' => 'Do you want to keep other Free Shipping methods when hide all other shipping methods is enabled?',
				'default' => 'no',
				'proFeature' => true,
			),
			'displayOrder' => array(
				'title' => __('Display Order', $this->id),
				'type' => 'select',
				'default' => 'first',
				'options' => array(
					'first' => __('First in the list', $this->id),
					'last' => __('Last in the list', $this->id),
				),
				'proFeature' => true,
			),
			'operator' => array(
				'title' => __('Free Shipping Requires...', $this->id),
				'type' => 'select',
				'default' => 'and',
				'options' => array(
					'and' => __('All conditions to match', $this->id),
					'or' => __('Any condition to match', $this->id),
				),
			),
		);

		$this->instance_form_fields = apply_filters($this->id . '_instance_form_fields', $this->instance_form_fields);
	}

	public function is_available($package)
	{
		if (!$this->is_enabled()) {
			return false;
		}

		$this->debug('Check if Free Shipping Per Package is available: ' . $this->instance_id);

		$conditionResults = apply_filters($this->id . '_condition_results', array(), $package, $this);

		$this->debug('Condition Results: ' . print_r($conditionResults, true));

		$numberOfResults = 0;
		$numberOfMatches = 0;
		foreach ($conditionResults as $key => $matched) {
			if (isset($this->instance_form_fields[$key])) {
				$numberOfResults += 1;
				if (!empty($matched)) {
					$numberOfMatches += 1;
				}
			}
		}

		$this->debug($numberOfMatches . ' matched out of ' . $numberOfResults);

		$this->isAvailable = false;

		$operator = $this->get_option('operator', 'and');
		if ($numberOfMatches == $numberOfResults) {
			$this->debug('All conditions have matched, so method will be available');

			$this->isAvailable = true;
		} else if ($operator == 'or' && $numberOfMatches > 0) {
			$this->debug('Some conditions have matched, so method will be available');

			$this->isAvailable = true;
		}

		$this->isAvailable = apply_filters($this->id . '_is_shipping_method_available', $this->isAvailable, $conditionResults, $package, $this);

		return $this->isAvailable;
	}

	public function calculate_shipping($package = array())
	{
		$this->debug('Add shipping method to the list');

		$this->add_rate(array(
			'label'   => $this->title,
			'cost'    => 0,
			'taxes'   => false,
			'package' => $package,
			'meta_data' => array(
				'_default' => $this->get_option('defaultShippingMethod', 'no'),
			)
		));
	}

	protected function debug($message, $type = 'notice')
	{
		if ($this->get_option('debug', 'no') != 'yes' || !current_user_can('administrator')) {
			return;
		}

		if (function_exists('wc_add_notice')) {
			wc_add_notice($message, $type);
		} else {
			error_log("$type: $message\n");
		}
	}
}

endif;
