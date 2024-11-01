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

namespace OneTeamSoftware\WooCommerce\FreeShippingPerPackage\Condition;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\FreeShippingCoupon')):

class FreeShippingCoupon extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'freeShippingCoupon' => array(
				'title' => __('Free Shipping Coupon', $this->id),
				'type' => 'checkbox',
				'description' => __('A Free Shipping coupon that is valid for all products in a package is required to get Free Shipping', $this->id),
				'default' => 'no',
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		if ($shippingMethod->get_option('freeShippingCoupon', 'no') == 'no') {
			return $conditionResults;
		}

		$hasValidCoupon = false;
		foreach (WC()->cart->applied_coupons as $code) {
			$conditionResults['values']['coupon'][] = $code;
			
			$coupon = new \WC_Coupon($code);

			if ($coupon->is_valid() && $coupon->enable_free_shipping() && $this->isCouponValidFor($coupon, $package)) {
				$hasValidCoupon = true;

				break;
			}
		}

		$conditionResults['freeShippingCoupon'] = $hasValidCoupon;

		return $conditionResults;
	}

	private function isCouponValidFor(\WC_Coupon $coupon, array $package)
	{
		if ($this->isCouponUnconditional($coupon)) {
			return true;
		}

		if (empty($package['contents'])) {
			return false;
		}

		$isValid = true;
		foreach ($package['contents'] as $item) { 
			$product = $item['data'];

			if (!$coupon->is_valid_for_product($product)) {
				$isValid = false;
				break;
			}
		}

		return $isValid;
	}

	private function isCouponUnconditional(\WC_Coupon $coupon)
	{
		if (count($coupon->get_product_ids()) > 0) {
			return false;
		}

		if (count($coupon->get_excluded_product_ids()) > 0) {
			return false;
		}

		if (count($coupon->get_product_categories()) > 0) {
			return false;
		}

		if (count($coupon->get_excluded_product_categories()) > 0) {
			return false;
		}

		if ($coupon->get_exclude_sale_items()) {
			return false;
		}

		return true;
	}
}

endif;
