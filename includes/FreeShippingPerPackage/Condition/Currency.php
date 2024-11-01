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

if (!class_exists(__NAMESPACE__ . '\\Currency')):

class Currency extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'currency' => array(
				'title' => __('Currency', $this->id),
				'type' => 'select',
				'options' => array_merge(array('' => __('Any', $this->id)), get_woocommerce_currencies()),
				'default' => '',
				'description' => __('Require currency to match selected value', $this->id),
				'proFeature' => true,
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		return $conditionResults;
	}
}

endif;
