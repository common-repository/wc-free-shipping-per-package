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

if (!class_exists(__NAMESPACE__ . '\\AbstractCondition')):

abstract class AbstractCondition
{
	protected $id;
	protected $priority;

	public function __construct($id, $priority = 10)
	{
		$this->id = $id;
		$this->priority = $priority; 
	}

	public function register()
	{
		add_filter($this->id . '_form_fields', array($this, 'addFormFields'), $this->priority, 1);
		add_filter($this->id . '_instance_form_fields', array($this, 'addInstanceFormFields'), $this->priority, 1);
		add_filter($this->id . '_condition_results', array($this, 'addConditionResults'), $this->priority, 3);
	}

	public function addFormFields(array $formFields)
	{
		return $formFields;
	}

	protected function getDiff($value1, $value2)
	{
		$diff = $value1 - $value2;
		if ($diff < 0) {
			$diff = 0;
		}

		return $diff;
	}

	public abstract function addInstanceFormFields(array $instanceFormFields);
	public abstract function addConditionResults(array $conditionResults, array $package, $shippingMethod);
}

endif;
