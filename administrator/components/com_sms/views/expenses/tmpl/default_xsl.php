<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;

$model = $this->getModel();
$total =0;

//GET SCHOOLS DATA
$app = JFactory::getApplication();
$params = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
$schools_address = $params->get('schools_address');
$schools_phone = $params->get('schools_phone');
$schools_email = $params->get('schools_email');
$schools_website = $params->get('schools_web');
$schools_currency = $params->get('currency');


require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($schools_name)
						->setLastModifiedBy($schools_name)
						->setTitle("Office 2007 XLSX ".JText::_('LABEL_EXPENSE_LIST')."")
						->setSubject("Office 2007 XLSX ".JText::_('LABEL_EXPENSE_LIST')."")
						->setDescription("Expense List for Office 2007 XLSX, generated using PHP classes.")
						->setKeywords("office 2007 openxml php")
						->setCategory(JText::_('LABEL_EXPENSE_LIST'));

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', JText::_('LABEL_EXPENSE_TITLE'))
        ->setCellValue('B1', JText::_('LABEL_EXPENSE_CATEGORY'))
        ->setCellValue('C1', JText::_('LABEL_EXPENSE_METHOD'))
		->setCellValue('D1', JText::_('LABEL_EXPENSE_DATE'))
		->setCellValue('E1', JText::_('LABEL_EXPENSE_ENTRY_BY'))
        ->setCellValue('F1', JText::_('LABEL_EXPENSE_AMMOUNT'));

		if (!empty($this->items)) :
			$total_row = count($this->items) + 2;
			foreach ($this->items as $i => $row) :
				$i++;
				$cn = $i+1;
				$total += $row->ammount;
				$title = $row->title;
			    $category_name = $model->getCatName($row->cat);
			    $entry_name = $model->getEntryName($row->uid);
				$ammount = number_format($row->ammount, 2).$schools_currency;
				$final_total = number_format($total, 2).$schools_currency;
				$method = $row->method;
				$date = $row->expense_date;
						
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$cn.'', $title);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$cn.'', $category_name);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$cn.'', $method);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$cn.'', $date);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$cn.'', $entry_name);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$cn.'', $ammount);
				
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$total_row.'', JText::_('LABEL_EXPENSE_TOTAL').':');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$total_row.'', $final_total);
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$cn.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$cn.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$total_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			endforeach;
		endif;
						
						
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:F1')
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFCCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40.30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30.30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30.30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30.30);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Expense List');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="expense_list.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;

?>


    


    
		
		