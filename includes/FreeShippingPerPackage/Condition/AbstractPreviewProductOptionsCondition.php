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

if (!class_exists(__NAMESPACE__ . '\\AbstractPreviewProductOptionsCondition')):

abstract class AbstractPreviewProductOptionsCondition extends AbstractCondition
{
	protected $optionKey;
	protected $name;

	public function __construct($id, $priority = 10)
	{
		parent::__construct($id, $priority);

		$this->optionKey = $this->getOptionKey();
		$this->name = $this->getName();
	}

	public function addInstanceFormFields(array $instanceFormFields)
	{
		$instanceFormFields = array_merge($instanceFormFields, array(
			$this->optionKey . 'Operator' => array(
				'title' => sprintf('%s %s', $this->name, __('Condition', $this->id)),
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'default' => 'and',
				'options' => array(
					'and' => __('All products have to match'),
					'or' => __('Any product has to match'),
				),
				'proFeature' => true,
			),
			$this->optionKey => array(
				'title' => $this->name,
				'type' => 'multiselect',
				'class' => 'wc-enhanced-select',
				'options' => array(),
				'description' => __('Products in a package should match any of the of the specified values to get Free Shipping', $this->id),
				'proFeature' => true,
			),
		));

		return $instanceFormFields;
	}

	public function addConditionResults(array $conditionResults, array $package, $shippingMethod)
	{
		return $conditionResults;
	}

	public abstract function getOptionKey();
	public abstract function getName();
}

endif;
