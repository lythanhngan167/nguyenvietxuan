<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelDiscount extends EShopModel
{

	public function __construct($config)
	{
		$config['translatable'] = false;
		parent::__construct($config);
	}

	function store(&$data)
	{
		if (count($data['discount_customergroups']))
		{
			$data['discount_customergroups'] = implode(',', $data['discount_customergroups']);
		}
		else
		{
			$data['discount_customergroups'] = '';
		}
		parent::store($data);
		//Delete discount elements first
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		if ($data['id'])
		{
			$query->delete('#__eshop_discountelements')
				->where('discount_id = ' . intval($data['id']));
			$db->setQuery($query);
			$db->execute();
		}
		$row = new EShopTable('#__eshop_discounts', 'id', $this->getDbo());
		$row->load($data['id']);
		$discountId = $data['id'];
		//Discount for products
		$productIds = $data['product_id'];
		if (count($productIds))
		{
			$query->clear();
			$query->insert('#__eshop_discountelements')
				->columns('discount_id, element_id, element_type');
			for ($i = 0; $i < count($productIds); $i++)
			{
				$productId = $productIds[$i];
				$query->values("$discountId, $productId, 'product'");
			}
			$db->setQuery($query);
			$db->execute();
		}
		//Discount for manufacturers
		$manufacturerIds = $data['manufacturer_id'];
		if (count($manufacturerIds))
		{
			$query->clear();
			$query->insert('#__eshop_discountelements')
				->columns('discount_id, element_id, element_type');
			for ($i = 0; $i < count($manufacturerIds); $i++)
			{
				$manufacturerId = $manufacturerIds[$i];
				$query->values("$discountId, $manufacturerId, 'manufacturer'");
			}
			$db->setQuery($query);
			$db->execute();
		}
		//Discount for categories
		$categoryIds = $data['category_id'];
		if (count($categoryIds))
		{
			$query->clear();
			$query->insert('#__eshop_discountelements')
				->columns('discount_id, element_id, element_type');
			for ($i = 0; $i < count($categoryIds); $i++)
			{
				$categoryId = $categoryIds[$i];
				$query->values("$discountId, $categoryId, 'category'");
			}
			$db->setQuery($query);
			$db->execute();
		}
		return true;
	}
	
	/**
	 * Function to copy discount and elements
	 * @see EShopModel::copy()
	 */
	function copy($id)
	{
		$copiedDiscountId = parent::copy($id);
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		// Discount elements
		$query->select('COUNT(*)')
			->from('#__eshop_discountelements')
			->where('discount_id = ' . intval($id));
		$db->setQuery($query);
		if ($db->loadResult())
		{
			$sql = 'INSERT INTO #__eshop_discountelements'
				. ' (discount_id, element_id, element_type)'
				. ' SELECT ' . $copiedDiscountId . ', element_id, element_type'
				. ' FROM #__eshop_discountelements'
				. ' WHERE discount_id = ' . intval($id);
			$db->setQuery($sql);
			$db->execute();
		}
		return $copiedDiscountId;
	}
	
	/**
	 * Method to remove discounts
	 *
	 * @access	public
	 * @return boolean True on success
	 * @since	1.5
	 */
	public function delete($cid = array())
	{
		//Remove discount elements
		if (count($cid))
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true);
			$query->delete('#__eshop_discountelements')
				->where('discount_id IN (' . implode(',', $cid) . ')');
			$db->setQuery($query);
			$db->execute();
		}
		parent::delete($cid);
	}
}