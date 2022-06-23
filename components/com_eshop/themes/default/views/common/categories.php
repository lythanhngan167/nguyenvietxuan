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

$span                   = intval(12 / $categoriesPerRow);
$rowFluidClass          = $bootstrapHelper->getClassMapping('row-fluid');
$spanClass              = $bootstrapHelper->getClassMapping('span' . $span);
?>
<div class="<?php echo $rowFluidClass; ?>">
	<?php
	$count = 0;
	foreach ($categories as $category) 
	{
		$categoryUrl = JRoute::_(EshopRoute::getCategoryRoute($category->id));
		?>
		<div class="<?php echo $spanClass; ?>">
			<div class="eshop-category-wrap">
				<div class="image">
					<a href="<?php echo $categoryUrl; ?>" title="<?php echo $category->category_page_title != '' ? $category->category_page_title : $category->category_name; ?>">
						<img src="<?php echo $category->image; ?>" alt="<?php echo $category->category_alt_image != '' ? $category->category_alt_image : $category->category_name; ?>" />	            
					</a>
	            </div>
				<div class="eshop-info-block">
					<h5>
						<a href="<?php echo $categoryUrl; ?>" title="<?php echo $category->category_page_title != '' ? $category->category_page_title : $category->category_name; ?>">
							<?php echo $category->category_name; ?>
						</a>
					</h5>
				</div>
			</div>
		</div>
		<?php
		$count++;
		if ($count % $categoriesPerRow == 0 && $count < count($categories))
		{
		?>
			</div><div class="<?php echo $rowFluidClass; ?>">
		<?php
		}
	}
	?>
</div>