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

if (!class_exists(__NAMESPACE__ . '\\DayOfTheWeek')):

class DayOfTheWeek extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'dayOfTheWeek' => array(
				'title' => __('Days of the Week', $this->id),
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'options' => array(),
				'description' => __('Free Shipping will be only offered on the specified days of the week', $this->id),
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
