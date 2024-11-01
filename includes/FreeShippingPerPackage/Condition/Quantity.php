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

if (!class_exists(__NAMESPACE__ . '\\Quantity')):

class Quantity extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'minQuantity' => array(
				'title' => __('Min Quantity', $this->id),
				'type' => 'number',
				'description' => __('Customer has to have min this quantity of the products in the same package to get Free Shipping', $this->id),
			),
			'maxQuantity' => array(
				'title' => __('Max Quantity', $this->id),
				'type' => 'number',
				'description' => __('Customer can have max this quantity of the products in the same package to get Free Shipping', $this->id),
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		$quantity = $this->getQuantity($package);
		$conditionResults['values']['quantity'] = $quantity;

		$minQuantity = $shippingMethod->get_option('minQuantity', 0);
		if (!empty($minQuantity)) {
			$conditionResults['minQuantity'] = $quantity >= $minQuantity;
		}

		$maxQuantity = $shippingMethod->get_option('maxQuantity', 0);
		if (!empty($maxQuantity)) {
			$conditionResults['maxQuantity'] = $quantity <= $maxQuantity;
		}

		return $conditionResults;
	}

	private function getQuantity(array $package) 
    {
		if (empty($package['contents'])) {
			return 0;
		}

        $quantity = 0;
        foreach ($package['contents'] as $item) { 
            $quantity += $item['quantity']; 
		}

        return $quantity;
	}
}

endif;
