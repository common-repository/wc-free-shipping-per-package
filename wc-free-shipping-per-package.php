<?php
/**
 * Plugin Name: Free Shipping Per Package for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/wc-free-shipping-per-package/
 * Description: Offers Free Shipping method when shipping package meets special conditions
 * Version: 1.0.11
 * Tested up to: 6.6
 * Author: OneTeamSoftware
 * Author URI: http://oneteamsoftware.com/
 * Developer: OneTeamSoftware
 * Developer URI: http://oneteamsoftware.com/
 * Text Domain: wc-free-shipping-per-package-pro
 * Domain Path: /languages
 * Copyright: © 2024 FlexRC, 604-1097 View St, V8V 0G9, Canada. Voice 604 800-7879
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

namespace OneTeamSoftware\WooCommerce\FreeShippingPerPackage;

defined('ABSPATH') || exit;

require_once(__DIR__ . '/includes/autoloader.php');

(new Plugin(__FILE__))->register();
