<?php
/**
 * @package	Schools Management System for Joomla
 * @author	zwebtheme.com
 * @copyright	(C) 2016-2019 zwebtheme. All rights reserved.
 * @license	https://codecanyon.net/item/school-management-system-for-joomla/18219198
 */

defined('_JEXEC') or die;


?>

<style type="text/css">
.itemContainerfirst{margin-left: 0px !important;}
.itemblock{
background: none repeat scroll 0 0 #f5f5f5;
    border: 1px solid #eee;
    margin: 3px 0;
    min-height: 50px;
}
.itemblock a {display: block;
    font-family: arial;
    font-size: 16px;
    margin: 0;
    padding: 12px;}
		
		.details-info{padding: 5px 15px;}
		.details-info table{font-family: arial;font-size: 16px;color: #888;}
		.lb{text-align: right;font-weight: Bold;width: 150px;}
		.department,
		.name_title,
		.teliphone,
		.emailadd {font-family: arial;font-size: 16px;color: #888;font-weight: Bold;}
		.extra td{font-family: arial;font-size: 16px;color: #888;border: 1px solid #ccc;}
		
</style>


<h1 style="padding-left: 15px;background: #f5f5f5;">Current CU Name:</h1>
<div class="row-fluid">
     <div class="span6">
		    <div class="details-info">
				<table  style="width: 100%;">
				 <tr><td class="lb">Participant Name:</td><td><?php echo $this->entry->name;?></td></tr>
				 <tr><td class="lb">Address:</td><td><?php echo $this->entry->address;?></td></tr>
				 <tr><td class="lb">City:</td><td><?php echo $this->entry->city;?></td></tr>
				 <tr><td class="lb">State:</td><td><?php echo $this->entry->state;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span6">
		    <div class="details-info">
				<table  style="width: 100%;">
				 <tr><td class="lb">Main Phone:</td><td><?php echo $this->entry->main_phone;?></td></tr>
				 <tr><td class="lb">Routing & Transit:</td><td><?php echo $this->entry->routing_transit;?></td></tr>
				 <tr><td class="lb">Zip:</td><td><?php echo $this->entry->zip;?></td></tr>
				</table>
		    </div>
		 </div>
</div>

<h1 style="padding-left: 15px;background: #f5f5f5;">Lobby Hours:</h1>
<div class="row-fluid">
     <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				 <tr><td class="lb">Monday:</td><td><?php echo $this->entry->lobby_monday;?></td></tr>
				 <tr><td class="lb">Thursday:</td><td><?php echo $this->entry->lobby_thursday;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				<tr><td class="lb">Tuesday:</td><td><?php echo $this->entry->lobby_tuesday;?></td></tr>
				<tr><td class="lb">Friday:</td><td><?php echo $this->entry->lobby_friday;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				<tr><td class="lb">Wednesday:</td><td><?php echo $this->entry->lobby_wednesday;?></td></tr>
				 <tr><td class="lb">Saturday:</td><td><?php echo $this->entry->lobby_saturday;?></td></tr>
				</table>
		    </div>
		 </div>
</div>

<h1 style="padding-left: 15px;background: #f5f5f5;">Drive-in Hours:</h1>
<div class="row-fluid">
     <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				 <tr><td class="lb">Monday:</td><td><?php echo $this->entry->drive_monday;?></td></tr>
				 <tr><td class="lb">Thursday:</td><td><?php echo $this->entry->drive_thursday;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				<tr><td class="lb">Tuesday:</td><td><?php echo $this->entry->drive_tuesday;?></td></tr>
				<tr><td class="lb">Friday:</td><td><?php echo $this->entry->drive_friday;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				<tr><td class="lb">Wednesday:</td><td><?php echo $this->entry->drive_wednesday;?></td></tr>
				 <tr><td class="lb">Saturday:</td><td><?php echo $this->entry->drive_saturday;?></td></tr>
				</table>
		    </div>
		 </div>
</div>

<h1 style="padding-left: 15px;background: #f5f5f5;">Fax Numbers:</h1>
<div class="row-fluid">
     <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				 <tr><td class="lb">Approvals - Large Deposit:</td><td><?php echo $this->entry->approval_large_deposit;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				<tr><td class="lb">Member Requests:</td><td><?php echo $this->entry->member_requests;?></td></tr>
				</table>
		    </div>
		 </div>
		 
		 <div class="span4">
		    <div class="details-info">
				<table  style="width: 100%;">
				<tr><td class="lb">Loans:</td><td><?php echo $this->entry->loans;?></td></tr>
				</table>
		    </div>
		 </div>
</div>

<div class="row-fluid">
   <div class="span12">
	    <table  style="width: 100%;" class="extra">
			 	<?php 
				$db =& JFactory::getDBO();
						$q = "SELECT * FROM  #__cu_extraentry WHERE entry_id ='".$this->entry->id."'  ORDER BY id ASC  ";
						$db->setQuery($q); 
						$entryArry = $db->loadObjectList();
						if(!empty($entryArry)){
						?>
						 <tr style="background: #f5f5f5;"><td class="department">Department </td> <td class="name_title">Name/Title </td> <td class="teliphone"> Telephone #/Ext </td> <td class="emailadd"> Email Address </td></tr>
			
						<?php
						}
						 foreach ( $entryArry as $entryd ) 
					  {
						
						//select answer count
						$entry_id = $entryd->entry_id;
		        $department = $entryd->department;
		        $nmae_title = $entryd->nmae_title;
						$telephone = $entryd->telephone;
						$email = $entryd->email;
						$id = $entryd->id;
						?>
						<tr><td><?php echo $department; ?> </td> <td ><?php echo $nmae_title; ?></td> <td> <?php echo $telephone; ?> </td> <td> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a> </td></tr>
				
						<?php
						}
				
				 ?>
			</table>
	 </div>

</div>

