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

if (!class_exists(__NAMESPACE__ . '\\Weight')):

class Weight extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$weightUnit = get_option('woocommerce_weight_unit');

		$instanceFormFields = array_merge($instanceFormFields, array(
			'minWeight' => array(
				'title' => sprintf(__('Min Weight (%s)', $this->id), $weightUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01, 'min' => 0),
				'description' => __('Total weight of all the products in a package should be at least this value to get Free Shipping', $this->id),
			),
			'maxWeight' => array(
				'title' => sprintf(__('Max Weight (%s)', $this->id), $weightUnit),
				'type' => 'number',
				'custom_attributes' => array('step' => 0.01, 'min' => 0),
				'description' => __('Total weight of all the products in a package should be up to this value to get Free Shipping', $this->id),
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		$weight = $this->getWeight($package);
		$conditionResults['values']['weight'] = $weight;

		$minWeight = $shippingMethod->get_option('minWeight', 0);
		if (!empty($minWeight)) {
			$conditionResults['minWeight'] = $weight >= $minWeight;
		}

		$maxWeight = $shippingMethod->get_option('maxWeight', 0);
		if (!empty($maxWeight)) {
			$conditionResults['maxWeight'] = $weight <= $maxWeight;
		}

		return $conditionResults;
	}

	protected function getWeight(array $package) 
    {
		if (empty($package['contents'])) {
			return 0;
		}

        $weight = 0;
        foreach ($package['contents'] as $item) { 
            $product = $item['data']; 

            $weight += floatval($product->get_weight()) * $item['quantity']; 
		}

        return $weight;
	}
}

endif;
