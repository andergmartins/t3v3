<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('CategoriesHelper', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/categories.php');

/**
 * Category Component Association Helper
 *
 * @package     Joomla.Site
 * @subpackage  com_categories
 * @since       3.0
 */
abstract class CategoryHelperAssociation
{
	public static $category_association = true;

	/**
	 * Method to get the associations for a given category
	 *
	 * @param   integer  $id         Id of the item
	 * @param   string   $extension  Name of the component
	 *
	 * @return  array   Array of associations for the component categories
	 *
	 * @since  3.0
	 */

	public static function getCategoryAssociations($id = 0, $extension = 'com_content')
	{
		$return = array();

		if ($id)
		{
			$associations = CategoriesHelper::getAssociations($id, $extension);
			foreach ($associations as $tag => $item)
			{
				$return[$tag] = ContentHelperRoute::getCategoryRoute($item, $tag);
			}
		}

		return $return;
	}
}
