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

if (!class_exists(__NAMESPACE__ . '\\Price')):

class Price extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'minSubtotal' => array(
				'title' => __('Min Subtotal', $this->id),
				'type' => 'price',
				'description' => __('Customer has to spend min this amount, before coupons, on the products in the same package to get Free Shipping', $this->id),
			),
			'maxSubtotal' => array(
				'title' => __('Max Subtotal', $this->id),
				'type' => 'price',
				'description' => __('Customer can only spend up to this amount, before coupons, on the products in the same package to get Free Shipping', $this->id),
			),
			'includeSubtotalTax' => array(
				'title' => __('Include Subtotal Tax', $this->id),
				'type' => 'checkbox',
				'label' => __('Subtotal tax will be included in the decision', $this->id),
			),
			'minTotal' => array(
				'title' => __('Min Total', $this->id),
				'type' => 'price',
				'description' => __('Customer has to spend min this amount, after coupons, on the products in the same package to get Free Shipping', $this->id),
			),
			'maxTotal' => array(
				'title' => __('Max Total', $this->id),
				'type' => 'price',
				'description' => __('Customer can only spend up to this amount, after coupons, on the products in the same package to get Free Shipping', $this->id),
			),
			'includeTotalTax' => array(
				'title' => __('Include Total Tax', $this->id),
				'type' => 'checkbox',
				'label' => __('Total tax will be included in the decision', $this->id),
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		$packageTotals = $this->getPackageTotals($package);
		
		$subtotal = $packageTotals['subtotal'];
		if ($shippingMethod->get_option('includeSubtotalTax', 'no') == 'yes') {
			$subtotal += $packageTotals['subtotalTax'];
		}

		$conditionResults['values']['subtotal'] = $subtotal;

		$total = $packageTotals['total'];
		if ($shippingMethod->get_option('includeTotalTax', 'no') == 'yes') {
			$total += $packageTotals['totalTax'];
		}

		$conditionResults['values']['total'] = $total;
		
		$minSubtotal = $shippingMethod->get_option('minSubtotal', 0);
		if (!empty($minSubtotal)) {
			$conditionResults['minSubtotal'] = $subtotal >= $minSubtotal;
		}

		$maxSubtotal = $shippingMethod->get_option('maxSubtotal', 0);
		if (!empty($maxSubtotal)) {
			$conditionResults['maxSubtotal'] = $subtotal <= $maxSubtotal;
		}

		$minTotal = $shippingMethod->get_option('minTotal', 0);
		if (!empty($minTotal)) {
			$conditionResults['minTotal'] = $total >= $minTotal;
		}

		$maxTotal = $shippingMethod->get_option('maxTotal', 0);
		if (!empty($maxTotal)) {
			$conditionResults['maxTotal'] = $total <= $maxSubtotal;
		}

		return $conditionResults;
	}

	protected function getPackageTotals(array $package) 
    {
		$subtotal = 0;
		$total = 0;
		$subtotalTax = 0;
		$totalTax = 0;

		if (!empty($package['contents']) && is_array($package['contents'])) {
			foreach ($package['contents'] as $item) {
				if (isset($item['line_subtotal'])) {
					$subtotal += floatval($item['line_subtotal']);
				}

				if (isset($item['line_subtotal_tax'])) {
					$subtotalTax += floatval($item['line_subtotal_tax']);
				}

				if (isset($item['line_total'])) {
					$total += floatval($item['line_total']);
				}

				if (isset($item['line_tax'])) {
					$totalTax += floatval($item['line_tax']);
				}				
			}
		}

		return array('subtotal' => $subtotal, 'total' => $total, 'subtotalTax' => $subtotalTax, 'totalTax' => $totalTax);
	}
}

endif;
