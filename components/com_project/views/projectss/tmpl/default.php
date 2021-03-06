<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Project
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();

$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canCreate = $user->authorise('core.create', 'com_project') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'projectsform.xml');
$canEdit = $user->authorise('core.edit', 'com_project') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'projectsform.xml');
$canCheckin = $user->authorise('core.manage', 'com_project');
$canChange = $user->authorise('core.edit.state', 'com_project');
$canDelete = $user->authorise('core.delete', 'com_project');
$user = JFactory::getUser();
$levelUser = $user->level;

if (isset($_GET['project']) && $_GET['project'] > 0) {
    $project_id = $_GET['project'];
} else {
    $project_id = $this->items[0]->id;
}
$projectInfo = $this->getProjectByID($project_id);
$isrecruitment = $projectInfo['is_recruitment'];


//print_r($this->items[0]);
//echo $this->getMaxPickByCat(167,$levelUser);
?>

<link href="<?php echo JUri::base(); ?>templates/protostar/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="<?php echo JUri::base(); ?>templates/protostar/js/bootstrap-toggle.min.js"></script>

<style>
    .listproject {
        padding-top: 20px;
    }

    .listproject .project {
        border: 1px solid #f9f9f9;
        height: 250px;
        text-align: center;
        background-color: #f3f3f3;
        padding-top: 10px;
    }

    .listproject input {
        text-align: center;
        margin: auto;
        margin-bottom: 20px;
    }
    .autobuy-data{
      float:right;
      padding-right:20px;
    }
    .project .titlecat {
        font-size: 20px;
    }

    @media only screen and (max-width: 768px) {
        .listproject input {
            width: 100%;
        }
        .autobuy-data{
          float:none;
          padding-right:20px;
          text-align:right;
          padding-top: 15px;
        }
    }

</style>
<?php if ($this->totalCaring >= 1000) { ?>
    <div class="alert alert-warning">
        <strong>L??u ??!</strong> B???n ch??? ???????c nh???n v?? ch??m s??c t???i ??a 20 li??n h???. ????? nh???n th??m vui l??ng Tr??? l???i li??n h???.
    </div>
<?php } ?>

<?php
  if ($levelUser == 0) {
    // $levelUser == 1
  ?>
    <div class="alert alert-warning">
        <strong>L??u ??!</strong> Level 0 kh??ng ???????c mua li??n h???. B???n vui l??ng li??n h??? Admin n??ng c???p ????? mua li??n h???.
    </div>
<?php } else { ?>


    <?php if ($isrecruitment == 0 && $levelUser == 2) { ?>
        <div class="alert alert-warning">
            <strong>L??u ??!</strong> Level 2 ch??? ???????c mua li??n h??? Tuy???n d???ng. B???n vui l??ng li??n h??? Admin n??ng c???p ????? mua
            li??n h??? Kh??ch h??ng.
        </div>
    <?php } ?>


<?php } ?>

<form action="<?php echo JRoute::_('index.php?option=com_project&view=projectss'); ?>" method="post"
      name="adminForm" id="adminForm">

    <?php //echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
    <div class="row">
      <!-- <input type="checkbox" data-toggle="toggle" data-on="Enabled" data-off="Disabled"> -->


        <div class="col-xs-12 col-md-8">
            D??? ??n: <select name="project" id="project">
                <?php foreach ($this->items as $i => $item) {
                    ?>
                    <option <?php if (isset($_GET['project']) && $_GET['project'] == $item->id) {
                        echo 'selected="selected"';
                    } ?> dir="<?php echo JRoute::_('index.php?option=com_project&view=projectss&Itemid='.PROJECT_PAGE.'&project=' . $item->id); ?>"
                         value="<?php echo $item->id ?>"><?php echo $item->title; ?></option>
                <?php } ?>
            </select>
            <a class="project-detail" href="<?php echo JRoute::_('index.php?option=com_project&view=projects&id=' . ((int)$_GET['project'] > 0 ? $_GET['project'] : 21)); ?>">
                Chi ti???t D??? ??n
            </a>
        </div>
        <?php
        if($user->level == 1 || $user->level == 2 || $user->level == 3){
        ?>
        <div class="autobuy-data col-xs-12 col-md-4">
        T??? ?????ng mua Li??n h??? (Data)
        <input type="checkbox" id="toggle-autobuy"  >
        <script>
          jQuery(function() {
            jQuery('#toggle-autobuy').bootstrapToggle({
              on: 'B???t',
              off: 'T???t'
            });
            <?php if($user->autobuy == 1){ ?>
              jQuery('#toggle-autobuy').bootstrapToggle('on');
            <?php }else{ ?>
              jQuery('#toggle-autobuy').bootstrapToggle('off');
            <?php } ?>
              jQuery('#toggle-autobuy').change(function() {
              var autobuy = jQuery(this).prop('checked');
              var on_off = 0;
              if(autobuy){
                on_off = 1;
              }else{
                on_off = 0;
              }
              setAutoBuy(on_off);
            })
          })

        </script>
      </div>
    <?php } ?>
    </div>
    <div class="row">
        <!-- <div class="col-xs-12"><h1>Kh??ch h??ng ??ang c??</h1></div> -->
        <div class="col-xs-12 listproject">
            <?php
            if (isset($_GET['project']) && $_GET['project'] > 0) {
                $project_id = $_GET['project'];
            } else {
                $project_id = $this->items[0]->id;
            }

            //$list = $this->getListCustomers(1);
            $listCat = $this->getListCategories(); ?>
            <?php
            $disabledInput = 0;
            $disabledButton = 0;

            if (($isrecruitment == 0 && $levelUser == 2) ) {
              //|| $levelUser == 1
                $disabledInput = 1;
                $disabledButton = 1;
            } else {
                $disabledInput = 0;
                $disabledButton = 0;
            }

            if ($this->totalCaring >= 1000) {
                $disabledInput = 1;
                $disabledButton = 1;
            }


            ?>
            <?php foreach ($listCat as $k => $cat) {
                if ($levelUser == 3 && $cat->id == 151) {
                    $disabledInput = 0;
                    $disabledButton = 0;
                }
                if ($levelUser == 3 && $cat->id == 150) {
                    $disabledInput = 0;
                    $disabledButton = 0;
                }

                ?>
                <div class="col-sm-6 col-xs-6 project"><strong class="titlecat"><?php echo $cat->title; ?></strong>

                    <p>S??? l?????ng ??ang c??: <strong><?php echo $this->getCountByCat($project_id, $cat->id); ?></strong></p>
                    <p>Mua t???i ??a: <strong><?php echo $this->getMaxPickByCat($cat->id, $levelUser); ?></strong></p>
                    <p>Gi?? / 1 li??n h???: <strong
                                class="price">
                            <?php echo $cat->id != DATA_RETURN ? number_format($projectInfo['price'], 0, ",", ".") : 0; ?> <?php echo BIZ_XU; ?></strong></p>
                    <input type="text" <?php if ($disabledInput == 1) { ?> disabled <?php } ?>
                           placeholder="Nh???p s??? l?????ng" value="" id="number_<?php echo $cat->id; ?>"
                           name="number_<?php echo $cat->id; ?>">
                    <button type="button" <?php if ($disabledButton == 1) { ?> disabled <?php } ?>
                            class="btn btn-primary"
                            onclick="buyCustomer('<?php echo $cat->title; ?>',<?php echo $cat->id; ?>,'<?php echo $cat->id != DATA_RETURN  ? number_format($projectInfo['price'], 0, ",", ".") : 0 ; ?>',<?php echo $cat->id != DATA_RETURN  ? $projectInfo['price'] : 0; ?>,<?php echo $this->getCountByCat($project_id, $cat->id); ?>,<?php echo $project_id; ?>,<?php echo $levelUser; ?>)">
                        Mua
                    </button>
                    <input type="hidden" id="max_pick_<?php echo $cat->id; ?>" name="max_pick_<?php echo $cat->id; ?>"
                           value="<?php echo $this->getMaxPickByCat($cat->id, $levelUser); ?>"/>
                </div>
            <?php } ?>

        </div>
    </div>


    <!-- <table class="table table-striped" id="projectsList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				<th width="5%">
	<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.state', $listDirn, $listOrder); ?>
</th>
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_SHORT_DESCRIPTION', 'a.short_description', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_DESCRIPTION', 'a.description', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_FILE_1', 'a.file_1', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_FILE_2', 'a.file_2', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_FILE_3', 'a.file_3', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_FILE_4', 'a.file_4', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort', 'COM_PROJECT_PROJECTSS_FILE_5', 'a.file_5', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_PROJECT_PROJECTSS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_project'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_project')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					<td class="center">
	<a class="btn btn-micro <?php echo $class; ?>" href="<?php echo ($canChange) ? JRoute::_('index.php?option=com_project&task=projects.publish&id=' . $item->id . '&state=' . (($item->state + 1) % 2), false, 2) : '#'; ?>">
	<?php if ($item->state == 1): ?>
		<i class="icon-publish"></i>
	<?php else: ?>
		<i class="icon-unpublish"></i>
	<?php endif; ?>
	</a>
</td>
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'projectss.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_project&view=projects&id=' . (int)$item->id); ?>">
				<?php echo $this->escape($item->title); ?></a>
				</td>
				<td>

					<?php echo $item->short_description; ?>
				</td>
				<td>

					<?php echo $item->description; ?>
				</td>
				<td>

					<?php
        if (!empty($item->file_1)) :
            $file_1Arr = (array)explode(',', $item->file_1);
            foreach ($file_1Arr as $singleFile) :
                if (!is_array($singleFile)) :
                    $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
                    echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_1">' . $singleFile . '</a> ';
                endif;
            endforeach;
        else:
            echo $item->file_1;
        endif; ?>				</td>
				<td>

					<?php
        if (!empty($item->file_2)) :
            $file_2Arr = (array)explode(',', $item->file_2);
            foreach ($file_2Arr as $singleFile) :
                if (!is_array($singleFile)) :
                    $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
                    echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_2">' . $singleFile . '</a> ';
                endif;
            endforeach;
        else:
            echo $item->file_2;
        endif; ?>				</td>
				<td>

					<?php
        if (!empty($item->file_3)) :
            $file_3Arr = (array)explode(',', $item->file_3);
            foreach ($file_3Arr as $singleFile) :
                if (!is_array($singleFile)) :
                    $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
                    echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_3">' . $singleFile . '</a> ';
                endif;
            endforeach;
        else:
            echo $item->file_3;
        endif; ?>				</td>
				<td>

					<?php
        if (!empty($item->file_4)) :
            $file_4Arr = (array)explode(',', $item->file_4);
            foreach ($file_4Arr as $singleFile) :
                if (!is_array($singleFile)) :
                    $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
                    echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_4">' . $singleFile . '</a> ';
                endif;
            endforeach;
        else:
            echo $item->file_4;
        endif; ?>				</td>
				<td>

					<?php
        if (!empty($item->file_5)) :
            $file_5Arr = (array)explode(',', $item->file_5);
            foreach ($file_5Arr as $singleFile) :
                if (!is_array($singleFile)) :
                    $uploadPath = 'media/upload' . DIRECTORY_SEPARATOR . $singleFile;
                    echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file_5">' . $singleFile . '</a> ';
                endif;
            endforeach;
        else:
            echo $item->file_5;
        endif; ?>				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_project&task=projectsform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_project&task=projectsform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table> -->

    <!-- <?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_project&task=projectsform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_PROJECT_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?> -->
</form>

<?php if ($canDelete) : ?>
    <script type="text/javascript">

        jQuery(document).ready(function () {
            jQuery('.delete-button').click(deleteItem);
        });

        function deleteItem() {

            if (!confirm("<?php echo JText::_('COM_PROJECT_DELETE_MESSAGE'); ?>")) {
                return false;
            }
        }
    </script>
<?php endif; ?>

<script type="text/javascript">
    function buyCustomer(titlecat, id, price, price1, max, projectid, level) {

        var number = jQuery('#number_' + id).val();
        var max_pick = jQuery('#max_pick_' + id).val();
        number = parseInt(number, 10);
        max_pick = parseInt(max_pick, 10);

        if (number > 0) {
            if (number > max_pick) {
                alert("B???n kh??ng th??? mua v?????t qu?? s??? l?????ng qui ?????nh : " + max_pick + " li??n h???.");
            } else if (number > max) {
                alert("B???n kh??ng th??? mua v?????t qu?? s??? l?????ng ??ang c??: " + max + " li??n h???.");
            } else {
                jQuery.ajax({
                    url: "<?php echo JUri::base(); ?>index.php?option=com_project&view=projectss&ajax=1&type=checkPickToday&catid=" + id + "&quantity=" + number + "&projectid=" + projectid,
                    success: function (result) {
                        // no login
                        if (result == '-1') {
                            alert("Vui l??ng ????ng nh???p!");
                            location.reload();
                        }
                        // charge money
                        if (result == '-4') {
                            alert("S??? BizXu kh??ng ????? ????? th???c hi???n giao d???ch. Vui l??ng n???p th??m!");
                            //location.href = "<?php echo JUri::base(); ?>index.php?Itemid=698";
                            return;
                        }
                        // over caring
                        if (result == '-3') {
                            alert("B???n ???? ?????t ????? s??? l?????ng Kh??ch h??ng ??ang ch??m s??c, kh??ng th??? mua th??m li??n h??? l??c n??y.");
                            location.reload();
                        }
                        // over maxpick
                        if(result == '-6'){
                          alert("B???n mua v?????t qu?? s??? l?????ng qui ?????nh.");
                          location.reload();
                        }

                        // is ok
                        if (result == '-2') {
                            var r = confirm("B???n c?? ch???c mu???n mua " + number + " li??n h???, danh m???c: " + titlecat + ", ????n gi??: " + price + " BizXu / li??n h???, t???ng c???ng: " + numberWithCommas(number * price1) + " BizXu");
                            if (r == true) {
                                jQuery.ajax({
                                    url: "<?php echo JUri::base(); ?>index.php?option=com_project&view=projectss&ajax=1&type=createOrder&catid=" + id + "&price=" + price1 + "&quantity=" + number + "&projectid=" + projectid,
                                    success: function (result) {
                                        if (result == '-1') {
                                            alert("Vui l??ng ????ng nh???p!");
                                            location.reload();
                                        }
                                        if (result == '-4') {
                                            alert("S??? BizXu kh??ng ????? ????? th???c hi???n giao d???ch. Vui l??ng n???p th??m.");
                                            //location.href = "<?php echo JUri::base(); ?>index.php?Itemid=698";
                                            return;
                                        }
                                        if (result == '-5') {
                                            alert("Hi???n t???i kh??ng c??n ????? s??? l?????ng li??n h??? b???n mu???n mua!");
                                            location.reload();
                                            return;
                                        }
                                        if (result == '-6') {
                                            alert("B???n mua v?????t qu?? s??? l?????ng qui ?????nh.");
                                            location.reload();
                                            return;
                                        }
                                        if (result == '-2') {
                                            alert("Hi???n t???i kh??ng c??n ????? s??? l?????ng li??n h??? b???n mu???n mua!");
                                            location.reload();
                                        }
                                        if (result == '1') {
                                            alert("Mua " + number + " li??n h??? th??nh c??ng!");
                                            location.reload();
                                        }
                                        if (result == '0') {
                                            alert("Mua li??n h??? th???t b???i, vui l??ng th??? l???i.");
                                            location.reload();
                                        }
                                    }
                                });

                            } else {
                                //alert("Mua th??nh c??ng "+number+ " li??n h???!");
                            }
                        }

                        // cannot buy
                        if (result == '0') {
                            alert("B???n ???? mua ????? s??? l?????ng qui ?????nh trong h??m nay!");
                        }else{
                          if(result != '-1' && result != '-2' && result != '-3' && result != '-4' && result != '-6' ){
                            alert("S??? l?????ng c??n l???i c?? th??? mua h??m nay l??: " + result + " li??n h???.");
                          }
                        }
                    }
                });

            }
        } else {
            alert("Vui l??ng nh???p s??? li??n h??? c???n mua!");
        }

    }

    function setAutoBuy(on_off) {
        jQuery.ajax({
            url: "<?php echo JUri::base(); ?>index.php?option=com_project&view=projectss&ajax=1&type=setAutoBuy&on_off=" + on_off,
            success: function (result) {
                // no login
                if (result == '-1') {
                    alert("Vui l??ng ????ng nh???p!");
                    location.reload();
                }

                //ok
                if (result == '-2') {
                  alert("C???p nh???t th??nh c??ng!");
                }

                //ok
                if (result == '-3') {
                  alert("C???p nh???t th???t b???i, vui l??ng th??? l???i sau!");
                }

            }
        });
    }

    jQuery('#project').change(function () {
        //alert(jQuery(this).attr('dir'));
        window.location = jQuery('option:selected', this).attr('dir');
    });

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>
