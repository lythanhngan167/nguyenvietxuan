<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
$model        = $this->getModel();
$exam_id      = JRequest::getVar('exam');
$class_id     = JRequest::getVar('class');
$section_id   = JRequest::getVar('section');
$subject_id   = JRequest::getVar('subjects');

$exam_name    = $model->getExamname($exam_id);
$subject_name = $model->getSubjectname($subject_id);

$app          = JFactory::getApplication();
$params       = JComponentHelper::getParams('com_sms');
$schools_name = $params->get('schools_name');
	
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($schools_name)
						 ->setLastModifiedBy($schools_name)
						 ->setTitle("Office 2007 XLSX ".JText::_(''.$exam_name.'-'.$subject_name.'')." ")
						 ->setSubject("Office 2007 XLSX ".JText::_(''.$exam_name.'-'.$subject_name.'')."")
						 ->setDescription("Exam mark List for Office 2007 XLSX, generated using PHP classes.")
						 ->setKeywords("office 2007 openxml php")
						 ->setCategory(''.$exam_name.'-'.$subject_name.'');

// Add some data
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', JText::_('LABEL_MARK_ROLL'))
					->setCellValue('B1', JText::_('LABEL_MARK_MARK'))
					->setCellValue('C1', JText::_('LABEL_MARK_COMMENT'));

						if (!empty($this->items)) :
						    $total_row = count($this->items) + 2;
						    foreach ($this->items as $i => $row) :
						        $i++;
				                $cn = $i+1;
						        $roll = $row->roll;
			                    $marks = $model->getInMark('marks',$exam_id,$class_id,$subject_id,$row->id);
						        $comment = $model->getInMark('comment',$exam_id,$class_id,$subject_id,$row->id);
						
						        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$cn.'', $roll);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$cn.'', $marks);
								$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$cn.'', $comment);
								$objPHPExcel->getActiveSheet()->getStyle('A'.$cn.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
						    endforeach;
						endif;
						
$objPHPExcel->getActiveSheet()
    ->getStyle('A1:C1')
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FFCCCCCC');
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10.30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30.30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(60.30);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle(''.$exam_name.'-'.$subject_name.'');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$exam_name.'_'.$subject_name.'.xlsx"');
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


    


    
		
		