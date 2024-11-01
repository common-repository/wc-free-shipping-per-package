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

if (!class_exists(__NAMESPACE__ . '\\Date')):

class Date extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			'fromDate' => array(
				'title' => __('From Date', $this->id),
				'type' => 'date',
				'class' => 'datepicker',
				'description' => __('Free Shipping will be only offered from that date', $this->id),
				'proFeature' => true,
			),
			'toDate' => array(
				'title' => __('To Date', $this->id),
				'type' => 'date',
				'class' => 'datepicker',
				'description' => __('Free Shipping will be only offered to that date', $this->id),
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
