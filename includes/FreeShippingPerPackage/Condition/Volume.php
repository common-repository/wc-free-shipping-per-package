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

if (!class_exists(__NAMESPACE__ . '\\Volume')):

class Volume extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'minVolume' => array(
				'title' => __('Min Volume', $this->id),
				'type' => 'number',
				'description' => __('Total volume (width * length * height) of all the products in a package should be at least this value to get Free Shipping', $this->id),
			),
			'maxVolume' => array(
				'title' => __('Max Volume', $this->id),
				'type' => 'number',
				'description' => __('Total volume (width * length * height) of all the products in a package should be up to this value to get Free Shipping', $this->id),
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		$volume = $this->getVolume($package);
		$conditionResults['values']['volume'] = $volume;

		$minVolume = $shippingMethod->get_option('minVolume', 0);
		if (!empty($minVolume)) {
			$conditionResults['minVolume'] = $volume >= $minVolume;
		}

		$maxVolume = $shippingMethod->get_option('maxVolume', 0);
		if (!empty($maxVolume)) {
			$conditionResults['maxVolume'] = $volume <= $maxVolume;
		}

		return $conditionResults;
	}

	protected function getVolume(array $package) 
    {
		if (empty($package['contents'])) {
			return 0;
		}

        $volume = 0;
        foreach ($package['contents'] as $item) { 
            $product = $item['data']; 

            $volume += floatval($product->get_width()) * floatval($product->get_height()) * floatval($product->get_length()) * $item['quantity']; 
		}

        return $volume;
	}
}

endif;
