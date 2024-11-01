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

if (!class_exists(__NAMESPACE__ . '\\Taxonomy')):

class Taxonomy extends AbstractPreviewProductOptionsCondition
{
	public function getOptionKey()
	{
		return 'taxonomies';
	}

	public function getName()
	{
		return __('Taxonomies', $this->id);
	}
}

endif;
