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
defined( '_JEXEC' ) or die();

$rootUri = JUri::root();
?>
<table class="adminlist table table-bordered" style="text-align: center;">
	<thead>
	<tr>
		<th class="title" width="40%"><?php echo JText::_('ESHOP_IMAGE'); ?></th>
		<th class="title" width="20%"><?php echo JText::_('ESHOP_ORDERING'); ?></th>
		<th class="title" width="20%"><?php echo JText::_('ESHOP_PUBLISHED'); ?></th>
		<th class="title" width="20%">&nbsp;</th>
	</tr>
	</thead>
	<tbody id="product_images_area">
	<?php
	$options = array();
	$options[] = JHtml::_('select.option', '1', Jtext::_('ESHOP_YES'));
	$options[] = JHtml::_('select.option', '0', Jtext::_('ESHOP_NO'));
	for ($i = 0; $n = count($this->productImages), $i < $n; $i++) {
		$productImage = $this->productImages[$i];
		?>
		<tr id="product_image_<?php echo $i; ?>" style="height: 100px;">
			<td style="text-align: center; vertical-align: middle;">
				<?php
				if (JFile::exists(JPATH_ROOT.'/media/com_eshop/products/'.$productImage->image))
				{
					$viewImage = JFile::stripExt($productImage->image).'-100x100.'.JFile::getExt($productImage->image);
					if (Jfile::exists(JPATH_ROOT.'/media/com_eshop/products/resized/'.$viewImage))
					{
						?>
						<img class="img-polaroid" src="<?php echo $rootUri . 'media/com_eshop/products/resized/' . $viewImage; ?>" />
						<?php
					}
					else
					{
						?>
						<img class="img-polaroid" src="<?php echo $rootUri . 'media/com_eshop/products/' . $productImage->image; ?>" />
						<?php
					}
				}
				?>
				<input type="hidden" class="inputbox" name="productimage_id[]" value="<?php echo $productImage->id; ?>" />
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<input class="input-small" type="text" name="productimage_ordering[]" size="5" maxlength="10" value="<?php echo $productImage->ordering; ?>" />
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<?php echo JHtml::_('select.genericlist', $options, 'productimage_published[]', ' class="inputbox"', 'value', 'text', $productImage->published); ?>
			</td>
			<td style="text-align: center; vertical-align: middle;">
				<input type="button" class="btn btn-small btn-primary" name="btnRemove" value="<?php echo JText::_('ESHOP_BTN_REMOVE'); ?>" onclick="removeProductImage(<?php echo $i; ?>);" />
			</td>
		</tr>
		<?php
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="4">
			<input type="button" class="btn btn-small btn-primary" name="btnAdd" value="<?php echo JText::_('ESHOP_BTN_ADD'); ?>" onclick="addProductImage();" />
		</td>
	</tr>
	</tfoot>
</table>
