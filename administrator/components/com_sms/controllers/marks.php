<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


class SmsControllerMarks extends SmsController
{
	
	function __construct(){
		parent::__construct();
	}
	
	
	/**
	** Import Excel File
	**/
	function importcsv(){
	    $model = $this->getModel('marks');
		$exam_id = JRequest::getVar('exam_id');
		$class_id = JRequest::getVar('class_id');
		$section_id = JRequest::getVar('section_id');
		$subject_id = JRequest::getVar('subject_id');
	    require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
	    
	    //Check valid spreadsheet has been uploaded
        if(isset($_FILES['spreadsheet'])){
            if($_FILES['spreadsheet']['tmp_name']){
                if(!$_FILES['spreadsheet']['error']){
                    $inputFile = $_FILES['spreadsheet']['name'];
                    $extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
                    if($extension == 'XLSX' || $extension == 'ODS'){
                        //Read spreadsheeet workbook
	                    try {
	                        $inputFile = $_FILES['spreadsheet']['tmp_name'];
	                        $inputFileType = PHPExcel_IOFactory::identify($inputFile);
	                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
	                        $objPHPExcel = $objReader->load($inputFile);
	                    }catch(Exception $e) {
	                        die($e->getMessage());
	                    }

                        //Get worksheet dimensions
                        $sheet = $objPHPExcel->getSheet(0); 
                        $highestRow = $sheet->getHighestRow(); 
                        $highestColumn = $sheet->getHighestColumn();
			            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
												
                        //Loop through each row of the worksheet in turn
                        for ($row = 1; $row <= $highestRow; $row++){ 
                            //  Read a row of data into an array
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                            for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                                $cell = $sheet->getCellByColumnAndRow($col, $row);
                                $val = $cell->getValue();
                                $dataArr[$row][$col] = $val;
                            }                   
                        }//end row for
																		
						//insert database
						unset($dataArr[1]);
                        foreach($dataArr as $val){
                            $roll = $val['0'];
							$mark = $val['1'];
							$comment = $val['2'];
							//get student id
							$student_id = $model->getStudentID($roll);
							//get student year
							$student_year = $model->getStudentYear($roll);
							if(!empty($student_id)){
								$id = $model->savemark( $mark,$comment, $exam_id,  $class_id, $subject_id, $student_id, $roll,$student_year );
							}
						}
						$msg ="Excel File successfully upload & mark stored  ! ";
						$link = 'index.php?option=com_sms&view=marks&exam_id='.$exam_id.'&class_id='.$class_id.'&section_id='.$section_id.'&subject_id='. $subject_id;
	                                $this->setRedirect($link, $msg);
				    }else{
                        echo "Please upload an XLSX or ODS file";
                    }
                }else{
                    echo $_FILES['spreadsheet']['error'];
                }
            }// End spreadsheet file tmp_name
        }// End spreadsheet file

	}
	
	
	/**
	** Mark List
	**/
	function getmarklist(){
		$exam_id = JRequest::getVar('exam');
		$class_id = JRequest::getVar('class_id');
		$section_id = JRequest::getVar('section');
		$subject_id = JRequest::getVar('subject');
	    $model = $this->getModel('marks');
	
	    if(!empty($exam_id) && !empty($class_id) && !empty($section_id) && !empty($subject_id)){
	        $id = $model->getMarkList($exam_id, $class_id,$section_id, $subject_id);
	        if(empty($id)){
				echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' </div>';
			}else{
				echo $id;
			}
	    }else{
	        if(empty($exam_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam ! </div>';}
	        else if(empty($class_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class ! </div>';}
	        else if(empty($section_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select section ! </div>';}
			else if(empty($subject_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select subject ! </div>';}
			else {
				echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam,class,section & subject ! </div>';
			}
	    }
	    JFactory::getApplication()->close();
	}
	
	/**
	** Save Mark
	**/
	function savemark(){
		$mark = JRequest::getVar('mark');
		$comment = JRequest::getVar('comment');
		$exam_id = JRequest::getVar('exam_id');
		$class_id = JRequest::getVar('class_id');
		$subject_id = JRequest::getVar('subject_id');
		$student_id = JRequest::getVar('sid');
		$year = JRequest::getVar('year');
		$roll = JRequest::getVar('roll');
		$model = $this->getModel('marks');
		$id = $model->savemark( $mark,$comment, $exam_id,  $class_id, $subject_id, $student_id, $roll,$year );
	
	    if (!empty($id)) {
			echo 'ok';
		}else {
			echo '<p style="text-align: center;"><span id="meg" style=" background: red;color: #fff;padding: 3px 33px;">Error</span></p>';
		}
		
		JFactory::getApplication()->close();
	}
	
	/**
	** Student List
	**/
	function getstudentlist(){
		$exam_id = JRequest::getVar('exam');
		$class_id = JRequest::getVar('class_id');
		$section_id = JRequest::getVar('section');
		$subject_id = JRequest::getVar('subject');
	    $model = $this->getModel('marks');
	    if(!empty($exam_id) && !empty($class_id) && !empty($section_id) && !empty($subject_id)){
	        echo $model->getstudentList($exam_id, $class_id,$section_id, $subject_id);
	    }else{
	        if(empty($exam_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam ! </div>';}
	        else if(empty($class_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select class ! </div>';}
	        else if(empty($section_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select section ! </div>';}
			else if(empty($subject_id)){echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select subject ! </div>';}
			else {
				echo $msg = '<div class="alert alert-no-items">'.JText::_('JGLOBAL_NO_MATCHING_RESULTS').' Please select exam,class,section & subject ! </div>';
			}
	    }
	    JFactory::getApplication()->close();
	 }
	 
	/**
	** Subject List
	**/
	function getsubjectlist(){
		$cid = JRequest::getVar('class_id');
		$model = $this->getModel('marks');
		echo $model->getsubjectList($cid);
		JFactory::getApplication()->close();
	}
	 
}
