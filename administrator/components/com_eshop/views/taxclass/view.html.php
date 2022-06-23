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
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewTaxclass extends EShopViewForm
{

	function _buildListArray(&$lists, $item)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($item->id)
		{
			$query->select("tax_id, based_on, priority")
				->from("#__eshop_taxrules")
				->where("taxclass_id=" . (int) $item->id);
			$db->setQuery($query);
			$taxrateIds = $db->loadObjectList();
			$query->clear();
			$query->select("id, tax_name AS name")
				->from("#__eshop_taxes")
				->where("published = 1");
			$db->setQuery($query);
			$taxrates = array();
			$taxrates = array_merge($taxrates, $db->loadObjectList());
			$baseonOptions = array();
			$baseonOptions[] = JHtml::_('select.option', 'shipping', JText::_('ESHOP_SHIPPING_ADDRESS'));
			$baseonOptions[] = JHtml::_('select.option', 'payment', JText::_('ESHOP_PAYMENT_ADDRESS'));
			$baseonOptions[] = JHtml::_('select.option', 'store', JText::_('ESHOP_STORE_ADDRESS'));
			$this->baseonOptions = $baseonOptions;
			$this->taxrates = $taxrates;
			$this->taxrateIds = $taxrateIds;
		}
		//Build assign products to taxclass
		//Products no tax
		$query->clear();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.product_name AS text')
				->from('#__eshop_products AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
				->where('a.published = 1')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
				->where('product_taxclass_id = 0')
				->order('b.product_name');
		$db->setQuery($query);
		$options = array();
		$options[]=JHTML::_( 'select.option', '-1', '-- ' . JText::_('ESHOP_ALL_PRODUCTS') . ' --');
		$options = array_merge($options, $db->loadObjectList());
		$lists['products_no_tax'] = JHtml::_('select.genericlist', $options, 'products_no_tax[]',
				array(
						'option.text.toHtml' => false,
						'option.text' => 'text',
						'option.value' => 'value',
						'list.attr' => ' class="inputbox chosen" multiple ',
						'list.select' => ''));
		//Products list
		$query->clear();
		$query = $db->getQuery(true);
		$query->select('a.id AS value, b.product_name AS text')
				->from('#__eshop_products AS a')
				->innerJoin('#__eshop_productdetails AS b ON (a.id = b.product_id)')
				->where('a.published = 1')
				->where('b.language = "' . JComponentHelper::getParams('com_languages')->get('site', 'en-GB') . '"')
				->order('b.product_name');
		$db->setQuery($query);
		$options = array();
		$options[]=JHTML::_( 'select.option', '-1', '-- ' . JText::_('ESHOP_ALL_PRODUCTS') . ' --');
		$options = array_merge($options, $db->loadObjectList());
		if ($item->id > 0)
		{
			$query->clear();
			$query->select('a.id')
					->from('#__eshop_products AS a')
					->where('product_taxclass_id = '. (int) $item->id);
			$db->setQuery($query);
			$selectedItems = $db->loadColumn();

		}
		else
		{
			$selectedItems = array();
		}
		$lists['products_list'] = JHtml::_('select.genericlist', $options, 'products_list[]',
				array(
						'option.text.toHtml' => false,
						'option.text' => 'text',
						'option.value' => 'value',
						'list.attr' => ' class="inputbox chosen" multiple ',
						'list.select' => $selectedItems));
		
		JFactory::getDocument()->addScriptDeclaration(EshopHtmlHelper::getTaxrateOptionsJs())->addScriptDeclaration(
				EshopHtmlHelper::getBaseonOptionsJs());
		EshopHelper::chosen();
	}
}