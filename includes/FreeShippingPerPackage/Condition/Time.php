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

if (!class_exists(__NAMESPACE__ . '\\Time')):

class Time extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'fromTime' => array(
				'title' => __('From Time', $this->id),
				'type' => 'time',
				'placeholder' => sprintf(__('Current time is: %s', $this->id), current_time('H:i')),
				'description' => __('Free Shipping will be only offered from that time', $this->id),
				'proFeature' => true,
			),
			'toTime' => array(
				'title' => __('To Time', $this->id),
				'type' => 'time',
				'placeholder' => sprintf(__('Current time is: %s', $this->id), current_time('H:i')),
				'description' => __('Free Shipping will be only offered to that time', $this->id),
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
