<?php
/**
 * @package Schools Management System for Joomla
 * @author  zwebtheme.com
 * @copyright   (C) 2016-2019 zwebtheme. All rights reserved.
 * @license https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;
class SmsControllerBackup extends JControllerLegacy
{
	
	function __construct()
	{
		parent::__construct();
	}


    /**
    ** Get download student CSV
    **/
    function download_students_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'UserID');

        // Student Basic Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Name');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Email');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Avatar');

        // Student Account Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Username');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Password');

        // Student Academic information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Class');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Roll');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Section');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Division');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Year');

        // Student Custom field data
        $alphabet    = range('A', 'Z');
        $sid         = SmsHelper::getFieldSectionID('student');
        $fields      = SmsHelper::getFieldList($sid);
        $total_field = count($fields);
        foreach ($fields as $key => $field_item) {
            $field_name  = $field_item->field_name;
            $field_index = $alphabet[$key+12].'1';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_index, $field_name);
        }

        // Get total students
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_students'))
            ->order('id ASC');
        $db->setQuery($query);
        $student_list = $db->loadObjectList();
        
        // Start students Loop
        foreach ($student_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->user_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$sr.'', $item->email);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$sr.'', $item->photo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$sr.'', $item->chabima);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$sr.'', $item->churanita);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$sr.'', $item->class);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$sr.'', $item->roll);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$sr.'', $item->section);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$sr.'', $item->division);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$sr.'', $item->year);

            foreach ($fields as $f => $field_item) {

                $field_query = $db->getQuery(true);
                $field_query
                    ->select($db->quoteName(array('data')))
                    ->from($db->quoteName('#__sms_fields_data'))
                    ->where($db->quoteName('fid') . ' = '. $db->quote($field_item->id))
                    ->where($db->quoteName('sid') . ' = '. $db->quote($sid))
                    ->where($db->quoteName('panel_id') . ' = '. $db->quote($item->id));
                $db->setQuery($field_query);
                $field_data = $db->loadResult();

                $field_index    = $alphabet[$f+12].$sr;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_index, $field_data);
            }
        } 

        
        // set file name
        $filename = "backup_students_".date('d_M_Y');

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');

        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        exit;
        
    }


    /**
    ** Upload Student's CSV
    **/
    function upload_students_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['csv'])){

            if($_FILES['csv']['tmp_name']){
                if(!$_FILES['csv']['error'])
                {

                    $filename=$_FILES["csv"]["tmp_name"];   
                    if($_FILES["csv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id            = $getData['0'];
                            $user_id       = $getData['1'];
                            // Student Basic Information
                            $name          = $getData['2'];
                            $alias         = str_replace(' ', '-', strtolower($name));
                            $email         = $getData['3'];
                            $avatar        = $getData['4'];
                            // Student Account Information
                            $username      = $getData['5'];
                            $password      = $getData['6'];
                            $password_md5  = md5($password);
                            // Student Academic information
                            $class         = $getData['7'];
                            $roll          = $getData['8'];
                            $section       = $getData['9'];
                            $division      = $getData['10'];
                            $year          = $getData['11'];

                            // Store User database
                            if(!empty($user_id)){
                                // store user
                                $user_exit_id = SmsHelper::selectSingleData('id', 'users', 'id', $user_id);
                                if(empty($user_exit_id)){
                                    // Get new one insert
                                    $user_new            = new stdClass();
                                    $user_new->id        = $user_id;
                                    $user_new->name      = $name;
                                    $user_new->username  = $username;
                                    $user_new->email     = $email;
                                    $user_new->password  = $password_md5;
                                    $user_insert = JFactory::getDbo()->insertObject('#__users', $user_new);
                                }else{
                                    // Get update user data
                                    $user_update            = new stdClass();
                                    $user_update->id        = $user_id;
                                    $user_update->name      = $name;
                                    $user_update->username  = $username;
                                    $user_update->email     = $email;
                                    $user_update->password  = $password_md5;
                                    $user_update = JFactory::getDbo()->updateObject('#__users', $user_update, 'id');
                                }


                                // store user group
                                $group_exit_id = SmsHelper::selectSingleData('group_id', 'user_usergroup_map', 'user_id', $user_id);
                                if(empty($group_exit_id)){
                                    // Get new one insert
                                    $user_group_id = SmsHelper::selectSingleData('id', 'usergroups', 'title', 'Students');
                                    $user_group_new            = new stdClass();
                                    $user_group_new->user_id       = $user_id;
                                    $user_group_new->group_id      = $user_group_id;
                                    $user_group_insert = JFactory::getDbo()->insertObject('#__user_usergroup_map', $user_group_new);
                                }


                            }

                            // store student information
                            $exit_student_id = SmsHelper::selectSingleData('id', 'sms_students', 'id', $id);
                            if(empty($exit_student_id)){
                                // get new one insert
                                $student_new             = new stdClass();
                                $student_new->id         = $id;
                                $student_new->alias      = $alias;
                                $student_new->name       = $name;
                                $student_new->chabima    = $username;
                                $student_new->churanita  = $password;
                                $student_new->email      = $email;
                                $student_new->photo      = $avatar;
                                $student_new->user_id    = $user_id;
                                $student_new->class      = $class;
                                $student_new->roll       = $roll;
                                $student_new->division   = $division;
                                $student_new->section    = $section;
                                $student_new->year       = $year;
                                $student_insert = JFactory::getDbo()->insertObject('#__sms_students', $student_new);
                            }else{
                                // get update
                                $student_update            = new stdClass();
                                $student_update->id         = $id;
                                $student_update->alias      = $alias;
                                $student_update->name       = $name;
                                $student_update->chabima    = $username;
                                $student_update->churanita  = $password;
                                $student_update->email      = $email;
                                $student_update->photo      = $avatar;
                                $student_update->user_id    = $user_id;
                                $student_update->class      = $class;
                                $student_update->roll       = $roll;
                                $student_update->division   = $division;
                                $student_update->section    = $section;
                                $student_update->year       = $year;
                                $student_data_update = JFactory::getDbo()->updateObject('#__sms_students', $student_update, 'id');
                            }

                            // store student year
                            if(!empty($id) && !empty($class) && !empty($roll) && !empty($section) && !empty($division) && !empty($year)){
                                $year_query = $db->getQuery(true);
                                $year_query
                                    ->select($db->quoteName(array('id')))
                                    ->from($db->quoteName('#__sms_student_year'))
                                    ->where($db->quoteName('sid') . ' = '. $db->quote($id))
                                    ->where($db->quoteName('class') . ' = '. $db->quote($class))
                                    ->where($db->quoteName('roll') . ' = '. $db->quote($roll))
                                    ->where($db->quoteName('section') . ' = '. $db->quote($section))
                                    ->where($db->quoteName('division') . ' = '. $db->quote($division))
                                    ->where($db->quoteName('year') . ' = '. $db->quote($year));
                                $db->setQuery($year_query);
                                $exit_year_id = $db->loadResult();

                                if(empty($exit_year_id)){
                                    // Insert new one
                                    $year_new            = new stdClass();
                                    $year_new->sid       = $id;
                                    $year_new->class     = $class;
                                    $year_new->roll      = $roll;
                                    $year_new->section   = $section;
                                    $year_new->division  = $division;
                                    $year_new->year      = $year;
                                    $year_insert = JFactory::getDbo()->insertObject('#__sms_student_year', $year_new);
                                }else{
                                    // Update data
                                    $year_update            = new stdClass();
                                    $year_update->id        = $exit_year_id;
                                    $year_update->sid       = $id;
                                    $year_update->class     = $class;
                                    $year_update->roll      = $roll;
                                    $year_update->section   = $section;
                                    $year_update->division  = $division;
                                    $year_update->year      = $year;
                                    $year_update_data = JFactory::getDbo()->updateObject('#__sms_student_year', $year_update, 'id');
                                }
                            }
                            

                            // Student Custom field data
                            $sid         = SmsHelper::getFieldSectionID('student');
                            $fields      = SmsHelper::getFieldList($sid);
                            foreach ($fields as $key => $field_item) {
                                $fid        = $field_item->id;
                                $type       = $field_item->type;
                                $student_id = $id;
                                $field_data = $getData[$key+12];

                                $field_query = $db->getQuery(true);
                                $field_query
                                    ->select($db->quoteName(array('id')))
                                    ->from($db->quoteName('#__sms_fields_data'))
                                    ->where($db->quoteName('fid') . ' = '. $db->quote($fid))
                                    ->where($db->quoteName('sid') . ' = '. $db->quote($sid))
                                    ->where($db->quoteName('panel_id') . ' = '. $db->quote($student_id));
                                $db->setQuery($field_query);
                                $old_id = $db->loadResult();
                                SmsHelper::saveFields($fid, $type, $sid, $field_data, $student_id,$old_id);
                            }
 
                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span> Successfully restored</p>';
                        
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span> Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Download Student's Avatar
    **/
    function download_students_avatar()
    {
        $filename = 'backup_students_avatar_'.date('d_M_Y').'.zip';
        SmsHelper::getZip('../components/com_sms/photo/students/', $filename);
    }

    /**
    ** Upload Student's Avatars
    **/
    function upload_students_avatar()
    {
        $reponse = array('status' => true);

        if(isset($_FILES['file'])){
            $zip = new ZipArchive;
            $file = $_FILES['file']['tmp_name'];
            chmod($file,0777);
            if ($zip->open($file) === TRUE) {
                $zip->extractTo('../components/com_sms/photo/students/');
                $zip->close();
                $msg = '<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
            } else {
               $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
            }
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }


    /**
    ** Get download teacher CSV
    **/
    function download_teachers_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'UserID');

        // Teacher Basic Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Name');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Email');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Avatar');

        // Teacher Account Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Username');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Password');

        // Teacher Academic information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Designation');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Class');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Section');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Subject');

        // Teacher Custom field data
        $alphabet    = range('A', 'Z');
        $sid         = SmsHelper::getFieldSectionID('teacher');
        $fields      = SmsHelper::getFieldList($sid);
        $total_field = count($fields);
        foreach ($fields as $key => $field_item) {
            $field_name  = $field_item->field_name;
            $field_index = $alphabet[$key+11].'1';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_index, $field_name);
        }

        // Get total teacher
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_teachers'))
            ->order('id ASC');
        $db->setQuery($query);
        $teacher_list = $db->loadObjectList();
        
        // Start Teacher Loop
        foreach ($teacher_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->user_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$sr.'', $item->email);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$sr.'', $item->photo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$sr.'', $item->chabima);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$sr.'', $item->churanita);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$sr.'', $item->designation);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$sr.'', $item->class);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$sr.'', $item->section);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$sr.'', $item->subject);

            foreach ($fields as $f => $field_item) {

                $field_query = $db->getQuery(true);
                $field_query
                    ->select($db->quoteName(array('data')))
                    ->from($db->quoteName('#__sms_fields_data'))
                    ->where($db->quoteName('fid') . ' = '. $db->quote($field_item->id))
                    ->where($db->quoteName('sid') . ' = '. $db->quote($sid))
                    ->where($db->quoteName('panel_id') . ' = '. $db->quote($item->id));
                $db->setQuery($field_query);
                $field_data = $db->loadResult();

                $field_index    = $alphabet[$f+11].$sr;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_index, $field_data);
            }
        } 

        
        // set file name
        $filename = "backup_teacher_".date('d_M_Y');

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');

        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        exit;
        
    }

    /**
    ** Upload Teachers CSV
    **/
    function upload_teachers_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['tcsv'])){

            if($_FILES['tcsv']['tmp_name']){
                if(!$_FILES['tcsv']['error'])
                {

                    $filename=$_FILES["tcsv"]["tmp_name"];   
                    if($_FILES["tcsv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id            = $getData['0'];
                            $user_id       = $getData['1'];
                            // Student Basic Information
                            $name          = $getData['2'];
                            $alias         = str_replace(' ', '-', strtolower($name));
                            $email         = $getData['3'];
                            $avatar        = $getData['4'];
                            // Student Account Information
                            $username      = $getData['5'];
                            $password      = $getData['6'];
                            $password_md5  = md5($password);
                            // Student Academic information
                            $designation   = $getData['7'];
                            $class         = $getData['8'];
                            $section       = $getData['9'];
                            $subject       = $getData['10'];

                            // Store User database
                            if(!empty($user_id)){
                                // store user
                                $user_exit_id = SmsHelper::selectSingleData('id', 'users', 'id', $user_id);
                                if(empty($user_exit_id)){
                                    // Get new one insert
                                    $user_new            = new stdClass();
                                    $user_new->id        = $user_id;
                                    $user_new->name      = $name;
                                    $user_new->username  = $username;
                                    $user_new->email     = $email;
                                    $user_new->password  = $password_md5;
                                    $user_insert = JFactory::getDbo()->insertObject('#__users', $user_new);
                                }else{
                                    // Get update user data
                                    $user_update            = new stdClass();
                                    $user_update->id        = $user_id;
                                    $user_update->name      = $name;
                                    $user_update->username  = $username;
                                    $user_update->email     = $email;
                                    $user_update->password  = $password_md5;
                                    $user_update = JFactory::getDbo()->updateObject('#__users', $user_update, 'id');
                                }


                                // store user group
                                $group_exit_id = SmsHelper::selectSingleData('group_id', 'user_usergroup_map', 'user_id', $user_id);
                                if(empty($group_exit_id)){
                                    // Get new one insert
                                    $user_group_id = SmsHelper::selectSingleData('id', 'usergroups', 'title', 'Teachers');
                                    $user_group_new            = new stdClass();
                                    $user_group_new->user_id       = $user_id;
                                    $user_group_new->group_id      = $user_group_id;
                                    $user_group_insert = JFactory::getDbo()->insertObject('#__user_usergroup_map', $user_group_new);
                                }


                            }

                            // store teacher information
                            $exit_teacher_id = SmsHelper::selectSingleData('id', 'sms_teachers', 'id', $id);
                            if(empty($exit_teacher_id)){
                                // get new one insert
                                $teacher_new              = new stdClass();
                                $teacher_new->id          = $id;
                                $teacher_new->alias       = $alias;
                                $teacher_new->name        = $name;
                                $teacher_new->chabima     = $username;
                                $teacher_new->churanita   = $password;
                                $teacher_new->email       = $email;
                                $teacher_new->photo       = $avatar;
                                $teacher_new->user_id     = $user_id;
                                $teacher_new->designation = $designation;
                                $teacher_new->class       = $class;
                                $teacher_new->section     = $section;
                                $teacher_new->subject     = $subject;
                                $teacher_insert = JFactory::getDbo()->insertObject('#__sms_teachers', $teacher_new);
                            }else{
                                // get update
                                $teacher_update              = new stdClass();
                                $teacher_update->id          = $id;
                                $teacher_update->alias       = $alias;
                                $teacher_update->name        = $name;
                                $teacher_update->chabima     = $username;
                                $teacher_update->churanita   = $password;
                                $teacher_update->email       = $email;
                                $teacher_update->photo       = $avatar;
                                $teacher_update->user_id     = $user_id;
                                $teacher_update->designation = $designation;
                                $teacher_update->class       = $class;
                                $teacher_update->section     = $section;
                                $teacher_update->subject     = $subject;
                                $teacher_data_update = JFactory::getDbo()->updateObject('#__sms_teachers', $teacher_update, 'id');
                            }

                            
                            // Student Custom field data
                            $sid         = SmsHelper::getFieldSectionID('teacher');
                            $fields      = SmsHelper::getFieldList($sid);
                            foreach ($fields as $key => $field_item) {
                                $fid        = $field_item->id;
                                $type       = $field_item->type;
                                $student_id = $id;
                                $field_data = $getData[$key+11];

                                $field_query = $db->getQuery(true);
                                $field_query
                                    ->select($db->quoteName(array('id')))
                                    ->from($db->quoteName('#__sms_fields_data'))
                                    ->where($db->quoteName('fid') . ' = '. $db->quote($fid))
                                    ->where($db->quoteName('sid') . ' = '. $db->quote($sid))
                                    ->where($db->quoteName('panel_id') . ' = '. $db->quote($student_id));
                                $db->setQuery($field_query);
                                $old_id = $db->loadResult();
                                SmsHelper::saveFields($fid, $type, $sid, $field_data, $student_id,$old_id);
                            }
 
                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span> Successfully restored</p>';
                        
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span> Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Download Teacher's Avatar
    **/
    function download_teachers_avatar()
    {
        $filename = 'backup_teachers_avatar_'.date('d_M_Y').'.zip';
        SmsHelper::getZip('../components/com_sms/photo/teachers/', $filename);
    }

    /**
    ** Upload Teacher's Avatars
    **/
    function upload_teachers_avatar()
    {
        $reponse = array('status' => true);

        if(isset($_FILES['file'])){
            $zip = new ZipArchive;
            $file = $_FILES['file']['tmp_name'];
            chmod($file,0777);
            if ($zip->open($file) === TRUE) {
                $zip->extractTo('../components/com_sms/photo/teachers/');
                $zip->close();
                $msg = '<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
            } else {
               $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
            }
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }


    /**
    ** Get download parent CSV
    **/
    function download_parents_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'UserID');

        // Student Basic Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Name');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Email');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Avatar');

        // Student Account Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Username');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Password');

        // Student Academic information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Students ID');
        
        // Student Custom field data
        $alphabet    = range('A', 'Z');
        $sid         = SmsHelper::getFieldSectionID('parent');
        $fields      = SmsHelper::getFieldList($sid);
        $total_field = count($fields);
        foreach ($fields as $key => $field_item) {
            $field_name  = $field_item->field_name;
            $field_index = $alphabet[$key+8].'1';
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_index, $field_name);
        }

        // Get total students
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_parents'))
            ->order('id ASC');
        $db->setQuery($query);
        $student_list = $db->loadObjectList();
        
        // Start students Loop
        foreach ($student_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->user_id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$sr.'', $item->email);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$sr.'', $item->photo);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$sr.'', $item->chabima);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$sr.'', $item->churanita);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$sr.'', $item->student_id);
            
            foreach ($fields as $f => $field_item) {

                $field_query = $db->getQuery(true);
                $field_query
                    ->select($db->quoteName(array('data')))
                    ->from($db->quoteName('#__sms_fields_data'))
                    ->where($db->quoteName('fid') . ' = '. $db->quote($field_item->id))
                    ->where($db->quoteName('sid') . ' = '. $db->quote($sid))
                    ->where($db->quoteName('panel_id') . ' = '. $db->quote($item->id));
                $db->setQuery($field_query);
                $field_data = $db->loadResult();

                $field_index    = $alphabet[$f+8].$sr;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($field_index, $field_data);
            }
        } 

        
        // set file name
        $filename = "backup_parents_".date('d_M_Y');

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');

        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        exit;
        
    }

    /**
    ** Upload Parent's CSV
    **/
    function upload_parent_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['parentcsv'])){

            if($_FILES['parentcsv']['tmp_name']){
                if(!$_FILES['parentcsv']['error'])
                {

                    $filename=$_FILES["parentcsv"]["tmp_name"];   
                    if($_FILES["parentcsv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id            = $getData['0'];
                            $user_id       = $getData['1'];
                            // Parent Basic Information
                            $name          = $getData['2'];
                            $alias         = str_replace(' ', '-', strtolower($name));
                            $email         = $getData['3'];
                            $avatar        = $getData['4'];
                            // Parent Account Information
                            $username      = $getData['5'];
                            $password      = $getData['6'];
                            $password_md5  = md5($password);
                            // Student  information
                            $student_id    = $getData['7'];
                            

                            // Store User database
                            if(!empty($user_id)){
                                // store user
                                $user_exit_id = SmsHelper::selectSingleData('id', 'users', 'id', $user_id);
                                if(empty($user_exit_id)){
                                    // Get new one insert
                                    $user_new            = new stdClass();
                                    $user_new->id        = $user_id;
                                    $user_new->name      = $name;
                                    $user_new->username  = $username;
                                    $user_new->email     = $email;
                                    $user_new->password  = $password_md5;
                                    $user_insert = JFactory::getDbo()->insertObject('#__users', $user_new);
                                }else{
                                    // Get update user data
                                    $user_update            = new stdClass();
                                    $user_update->id        = $user_id;
                                    $user_update->name      = $name;
                                    $user_update->username  = $username;
                                    $user_update->email     = $email;
                                    $user_update->password  = $password_md5;
                                    $user_update = JFactory::getDbo()->updateObject('#__users', $user_update, 'id');
                                }


                                // store user group
                                $group_exit_id = SmsHelper::selectSingleData('group_id', 'user_usergroup_map', 'user_id', $user_id);
                                if(empty($group_exit_id)){
                                    // Get new one insert
                                    $user_group_id = SmsHelper::selectSingleData('id', 'usergroups', 'title', 'Parents');
                                    $user_group_new            = new stdClass();
                                    $user_group_new->user_id       = $user_id;
                                    $user_group_new->group_id      = $user_group_id;
                                    $user_group_insert = JFactory::getDbo()->insertObject('#__user_usergroup_map', $user_group_new);
                                }


                            }

                            // store parent information
                            $exit_parent_id = SmsHelper::selectSingleData('id', 'sms_parents', 'id', $id);
                            if(empty($exit_parent_id)){
                                // get new one insert
                                $parent_new             = new stdClass();
                                $parent_new->id         = $id;
                                $parent_new->alias      = $alias;
                                $parent_new->name       = $name;
                                $parent_new->chabima    = $username;
                                $parent_new->churanita  = $password;
                                $parent_new->email      = $email;
                                $parent_new->photo      = $avatar;
                                $parent_new->user_id    = $user_id;
                                $parent_new->student_id = $student_id;
                                $student_insert = JFactory::getDbo()->insertObject('#__sms_parents', $parent_new);
                            }else{
                                // get update
                                $parent_update            = new stdClass();
                                $parent_update->id         = $id;
                                $parent_update->alias      = $alias;
                                $parent_update->name       = $name;
                                $parent_update->chabima    = $username;
                                $parent_update->churanita  = $password;
                                $parent_update->email      = $email;
                                $parent_update->photo      = $avatar;
                                $parent_update->user_id    = $user_id;
                                $parent_update->student_id = $student_id;
                                $parent_data_update = JFactory::getDbo()->updateObject('#__sms_parents', $parent_update, 'id');
                            }

                            // Parent Custom field data
                            $sid         = SmsHelper::getFieldSectionID('parent');
                            $fields      = SmsHelper::getFieldList($sid);
                            foreach ($fields as $key => $field_item) {
                                $fid        = $field_item->id;
                                $type       = $field_item->type;
                                $parent_id  = $id;
                                $field_data = $getData[$key+8];

                                $field_query = $db->getQuery(true);
                                $field_query
                                    ->select($db->quoteName(array('id')))
                                    ->from($db->quoteName('#__sms_fields_data'))
                                    ->where($db->quoteName('fid') . ' = '. $db->quote($fid))
                                    ->where($db->quoteName('sid') . ' = '. $db->quote($sid))
                                    ->where($db->quoteName('panel_id') . ' = '. $db->quote($parent_id));
                                $db->setQuery($field_query);
                                $old_id = $db->loadResult();
                                SmsHelper::saveFields($fid, $type, $sid, $field_data, $parent_id,$old_id);
                            }
 
                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span> Successfully restored</p>';
                        
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span> Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }


    /**
    ** Download Parent's Avatar
    **/
    function download_parents_avatar()
    {
        $filename = 'backup_parents_avatar_'.date('d_M_Y').'.zip';
        SmsHelper::getZip('../components/com_sms/photo/parents/', $filename);
    }

    /**
    ** Upload Parent's Avatars
    **/
    function upload_parents_avatar()
    {
        $reponse = array('status' => true);

        if(isset($_FILES['file'])){
            $zip = new ZipArchive;
            $file = $_FILES['file']['tmp_name'];
            chmod($file,0777);
            if ($zip->open($file) === TRUE) {
                $zip->extractTo('../components/com_sms/photo/parents/');
                $zip->close();
                $msg = '<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
            } else {
               $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
            }
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Get download Class CSV
    **/
    function download_class_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Class information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Class ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Class Title');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Class Section');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Class Division');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Class Subject');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Class Grade System');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Class Status');

        // Get class 
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_class'))
            ->order('id ASC');
        $db->setQuery($query);
        $class_list = $db->loadObjectList();
        
        foreach ($class_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->class_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->section);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$sr.'', $item->division);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$sr.'', $item->subjects);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$sr.'', $item->grade_system);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$sr.'', $item->published);
        } 

        // set file name
        $filename = "backup_class_".date('d_M_Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
        exit;
    }

    /**
    ** Get download subject CSV
    **/
    function download_subject_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Subject information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Subject ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Subject Title');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Subject Shot Name');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Subject Code');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Subject Status');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Subject Order');

        // Get Subject 
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_subjects'))
            ->order('id ASC');
        $db->setQuery($query);
        $subject_list = $db->loadObjectList();
        
        foreach ($subject_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->subject_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->subject_shot_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$sr.'', $item->subject_code);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$sr.'', $item->published);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$sr.'', $item->order_number);
        } 

        // set file name
        $filename = "backup_subject_".date('d_M_Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
        exit;
    }

    /**
    ** Get download section CSV
    **/
    function download_section_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Section Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Section ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Section Title');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Section Status');

        // Get Section 
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_sections'))
            ->order('id ASC');
        $db->setQuery($query);
        $section_list = $db->loadObjectList();
        
        foreach ($section_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->section_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->published);
        } 

        // set file name
        $filename = "backup_section_".date('d_M_Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
        exit;
    }

    /**
    ** Get download Division CSV
    **/
    function download_division_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Division Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Division ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Division Title');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Division Status');

        // Get Division 
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_division'))
            ->order('id ASC');
        $db->setQuery($query);
        $division_list = $db->loadObjectList();
        
        foreach ($division_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->division_name);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->published);
        } 

        // set file name
        $filename = "backup_division_".date('d_M_Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
        exit;
    }

    /**
    ** Get download year CSV
    **/
    function download_year_csv(){
        require_once( JPATH_COMPONENT_ADMINISTRATOR.'/libraries/phpexcel/library/PHPExcel.php' );
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Academic Year Information
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Year ID');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Year Title');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Year Status');

        // Get Year 
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query
            ->select('*')
            ->from($db->quoteName('#__sms_academic_year'))
            ->order('id ASC');
        $db->setQuery($query);
        $year_list = $db->loadObjectList();
        
        foreach ($year_list as $key => $item) {
            $sr = $key +2;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$sr.'', $item->id);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$sr.'', $item->year);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$sr.'', $item->published);
        } 

        // set file name
        $filename = "backup_year_".date('d_M_Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
        exit;
    }

    /**
    ** Upload class CSV
    **/
    function upload_class_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['class_csv'])){

            if($_FILES['class_csv']['tmp_name']){
                if(!$_FILES['class_csv']['error'])
                {

                    $filename=$_FILES["class_csv"]["tmp_name"];   
                    if($_FILES["class_csv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id             = $getData['0'];
                            $class_name     = $getData['1'];
                            $section        = $getData['2'];
                            $division       = $getData['3'];
                            $subjects       = $getData['4'];
                            $grade_system   = $getData['5'];
                            $published      = $getData['6'];
                            
                            // store class information
                            $exit_class_id = SmsHelper::selectSingleData('id', 'sms_class', 'id', $id);
                            if(empty($exit_class_id)){
                                // get new one insert
                                $class_new                  = new stdClass();
                                $class_new->id              = $id;
                                $student_new->class_name    = $class_name;
                                $student_new->section       = $section;
                                $student_new->division      = $division;
                                $student_new->subjects      = $subjects;
                                $student_new->grade_system  = $grade_system;
                                $student_new->published     = $published;
                                $student_insert = JFactory::getDbo()->insertObject('#__sms_class', $class_new);
                            }else{
                                // get update
                                $class_update                = new stdClass();
                                $class_update->id            = $id;
                                $class_update->class_name    = $class_name;
                                $class_update->section       = $section;
                                $class_update->division      = $division;
                                $class_update->subjects      = $subjects;
                                $class_update->grade_system  = $grade_system;
                                $class_update->published     = $published;
                                $class_data_update = JFactory::getDbo()->updateObject('#__sms_class', $class_update, 'id');
                            }

                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Upload subject CSV
    **/
    function upload_subject_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['subject_csv'])){

            if($_FILES['subject_csv']['tmp_name']){
                if(!$_FILES['subject_csv']['error'])
                {

                    $filename=$_FILES["subject_csv"]["tmp_name"];   
                    if($_FILES["subject_csv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id                 = $getData['0'];
                            $subject_name       = $getData['1'];
                            $subject_shot_name  = $getData['2'];
                            $subject_code       = $getData['3'];
                            $published          = $getData['4'];
                            $order_number       = $getData['5'];
                            
                            // store class information
                            $exit_subject_id = SmsHelper::selectSingleData('id', 'sms_subjects', 'id', $id);
                            if(empty($exit_subject_id)){
                                // get new one insert
                                $subject_new                     = new stdClass();
                                $subject_new->id                 = $id;
                                $subject_new->subject_name       = $subject_name;
                                $subject_new->subject_shot_name  = $subject_shot_name;
                                $subject_new->subject_code       = $subject_code;
                                $subject_new->published          = $published;
                                $subject_new->order_number       = $order_number;
                                $subject_insert = JFactory::getDbo()->insertObject('#__sms_subjects', $subject_new);
                            }else{
                                // get update
                                $subject_update                     = new stdClass();
                                $subject_update->id                 = $id;
                                $subject_update->subject_name       = $subject_name;
                                $subject_update->subject_shot_name  = $subject_shot_name;
                                $subject_update->subject_code       = $subject_code;
                                $subject_update->published          = $published;
                                $subject_update->order_number       = $order_number;
                                $subject_data_update = JFactory::getDbo()->updateObject('#__sms_subjects', $subject_update, 'id');
                            }

                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Upload Section CSV
    **/
    function upload_section_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['section_csv'])){

            if($_FILES['section_csv']['tmp_name']){
                if(!$_FILES['section_csv']['error'])
                {

                    $filename=$_FILES["section_csv"]["tmp_name"];   
                    if($_FILES["section_csv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id                 = $getData['0'];
                            $section_name       = $getData['1'];
                            $published          = $getData['2'];
                            
                            // store class information
                            $exit_section_id = SmsHelper::selectSingleData('id', 'sms_sections', 'id', $id);
                            if(empty($exit_section_id)){
                                // get new one insert
                                $section_new                     = new stdClass();
                                $section_new->id                 = $id;
                                $section_new->section_name       = $section_name;
                                $section_new->published          = $published;
                                $section_insert = JFactory::getDbo()->insertObject('#__sms_sections', $section_new);
                            }else{
                                // get update
                                $section_update                     = new stdClass();
                                $section_update->id                 = $id;
                                $section_update->section_name       = $section_name;
                                $section_update->published          = $published;
                                $section_data_update = JFactory::getDbo()->updateObject('#__sms_sections', $section_update, 'id');
                            }

                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Upload Division CSV
    **/
    function upload_division_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['division_csv'])){

            if($_FILES['division_csv']['tmp_name']){
                if(!$_FILES['division_csv']['error'])
                {

                    $filename=$_FILES["division_csv"]["tmp_name"];   
                    if($_FILES["division_csv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id                 = $getData['0'];
                            $division_name       = $getData['1'];
                            $published          = $getData['2'];
                            
                            // store class information
                            $exit_division_id = SmsHelper::selectSingleData('id', 'sms_division', 'id', $id);
                            if(empty($exit_division_id)){
                                // get new one insert
                                $division_new                     = new stdClass();
                                $division_new->id                 = $id;
                                $division_new->division_name      = $division_name;
                                $division_new->published          = $published;
                                $division_insert = JFactory::getDbo()->insertObject('#__sms_division', $division_new);
                            }else{
                                // get update
                                $division_update                     = new stdClass();
                                $division_update->id                 = $id;
                                $division_update->division_name      = $division_name;
                                $division_update->published          = $published;
                                $division_data_update = JFactory::getDbo()->updateObject('#__sms_division', $division_update, 'id');
                            }

                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }

    /**
    ** Upload Year CSV
    **/
    function upload_year_csv(){
        $db = JFactory::getDBO();
        $reponse = array('status' => true);

        if(isset($_FILES['year_csv'])){

            if($_FILES['year_csv']['tmp_name']){
                if(!$_FILES['year_csv']['error'])
                {

                    $filename=$_FILES["year_csv"]["tmp_name"];   
                    if($_FILES["year_csv"]["size"] > 0){

                        $file = fopen($filename, "r");

                        // read the first line and ignore it
                        fgets($file); 
                        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

                            $id                 = $getData['0'];
                            $year               = $getData['1'];
                            $published          = $getData['2'];
                            
                            // store class information
                            $exit_year_id = SmsHelper::selectSingleData('id', 'sms_academic_year', 'id', $id);
                            if(empty($exit_year_id)){
                                // get new one insert
                                $year_new                     = new stdClass();
                                $year_new->id                 = $id;
                                $year_new->year               = $year;
                                $year_new->published          = $published;
                                $year_insert = JFactory::getDbo()->insertObject('#__sms_academic_year', $year_new);
                            }else{
                                // get update
                                $year_update                 = new stdClass();
                                $year_update->id             = $id;
                                $year_update->year           = $year;
                                $year_update->published      = $published;
                                $year_data_update = JFactory::getDbo()->updateObject('#__sms_academic_year', $year_update, 'id');
                            }

                        }

                        fclose($file);
                        $msg ='<p style="color: green;"><span class="fa fa-check" ></span>Successfully restored</p>';
                    }


                }else{
                    $msg = $_FILES['csv']['error'];
                }
            }// End spreadsheet file tmp_name
        }else{
            $msg = '<p style="color: red;"><span class="fa fa-exclamation-triangle"></span>Restored failed</p>';
        }

        $reponse['html'] = $msg;
        echo json_encode($reponse);
        JFactory::getApplication()->close();
    }



}

