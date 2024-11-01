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

if (!class_exists(__NAMESPACE__ . '\\UserRole')):

class UserRole extends AbstractCondition
{
	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields += array(
			'userRoles' => array(
				'title' => __('User Roles', $this->id),
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'options' => array(),
				'description' => __('Only Users with the specified Roles can get Free Shipping', $this->id),
				'proFeature' => true,
			),
		);

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		return $conditionResults;
	}
}

endif;
