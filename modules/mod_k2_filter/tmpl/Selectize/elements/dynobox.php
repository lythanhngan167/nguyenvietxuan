		<script type="text/javascript">
			var checker = 0;
			var initbox = '';
			var init_selected_count = 0;
		
			jQuery(document).ready(function() {
				//on page load event
				jQuery("#K2FilterBox<?php echo $module->id; ?> select").each(function() {
					if(jQuery(this).find(":selected").val() != "") {
						initbox = jQuery(this)[0];
						init_selected_count = 1;
						dynobox<?php echo $module->id; ?>(initbox);
						return false;
					}
				});
				
				//on select box change event
				jQuery("#K2FilterBox<?php echo $module->id; ?> form").change(function(event) {
					var elemIndex = jQuery('#K2FilterBox<?php echo $module->id; ?> select').index(event.target);
					dynobox<?php echo $module->id; ?>(event.target);
					if(checker == 0
						|| elemIndex < jQuery('#K2FilterBox<?php echo $module->id; ?> select').index(initbox)
					) {
						initbox = event.target;
						jQuery("#K2FilterBox<?php echo $module->id; ?> form").find("select").each(function() {
							if(this.value.length > 0) {
								init_selected_count++;
							}
						});
						checker = 1;
					}
					else {
						var init_selected = jQuery(event.target).find("option:selected");
						if(
							jQuery(event.target).attr("name") == jQuery(initbox).attr("name") 
							&& 
							(init_selected.hasClass("empty") || init_selected.length == 0)
						) {
							checker = 0;
							init_selected_count = 0;
						}
					}
					
					//if is initial box, then clear another boxes
					if(event.target == initbox) {
						jQuery.each(jQuery("#K2FilterBox<?php echo $module->id; ?> form").find("select[name*=searchword].selectized").not(event.target), function(k, el) {
							el.selectize.clear();
						});
					}
				});
				
				jQuery("#K2FilterBox<?php echo $module->id; ?> .k2filter-cell").each(function() {
					var select = jQuery(this).find("select");
					if(select.length > 0) {
						jQuery(this).prepend("<div class='dynoloader' style='display: none; z-index: 1000;'><img src='<?php echo JURI::root(); ?>modules/mod_k2_filter/assets/images/loading.png' style='width: 20px;' /></div>");
					}
				});
			}); 
			
			function dynobox<?php echo $module->id; ?>(target) {
				var form = jQuery("#K2FilterBox<?php echo $module->id; ?> form");
				var url = jQuery("#K2FilterBox<?php echo $module->id; ?> form:eq(0)").attr("action");
				var fields = form.find("select");
				
				var parent_block = jQuery(target).parent().parent();
				form.find('div.k2filter-cell').not(parent_block).find(".dynoloader").show();
				
				var field_type = "";
				var field_id = "";
				<?php 
					foreach($field_types as $field) {
						echo "field_type += '&field_type[]={$field->type}';\r\n"; 
						echo "field_id += '&field_id[]={$field->id}';\r\n"; 
					}
				?>
				
				var query = jQuery("#K2FilterBox<?php echo $module->id; ?> form").find(":input").filter(function () {
							return jQuery.trim(this.value).length > 0
						}).serialize();
				
				var selected_count_current = 0;
				jQuery("#K2FilterBox<?php echo $module->id; ?> form").find("select").each(function() {
					if(this.value.length > 0) {
						selected_count_current++;
					}
				});

				jQuery.ajax({
					dataType: "json",
					data: query + "&format=dynobox" + field_type + field_id,
					type: "GET",
					url: url,
					success: function(res) {
						if(res.length > 0) {
							jQuery(res).each(function(k, field) {								
								if(field.name == 'category_select' || field.name == 'category_multiple_select') {
									var filter = form.find("select[name*=category]");

									if(typeof filter.attr("name") === 'undefined') {
										return;
									}
									
									filter.find("option").not(".empty").each(function(k) {
										var form_val = jQuery(this).val();
										if(
											jQuery.inArray(form_val, field.values) > -1 
											|| 
											(
												((target.value.length == 0 && selected_count_current == init_selected_count)
												||
												jQuery(target).attr("name") == jQuery(initbox).attr("name")
												)
												&& 
												jQuery(initbox).attr("name").indexOf("category") > -1
											)
										) {
											jQuery(this).show();
											if(filter.next().attr("type") == "button") {
												filter.multiselect("widget").find(".ui-multiselect-checkboxes li").eq(k).show();
											}
										}
										else {
											if(jQuery(target).attr("name") != filter.attr("name")) {
												jQuery(this).hide();
												if(filter.next().attr("type") == "button") {
													filter.multiselect("widget").find(".ui-multiselect-checkboxes li").eq(k).hide();
												}
											}
										}
									});	
								}
								else if(field.name == 'tag_select' || field.name == 'tag_multi_select') {
									var filter = form.find("select[name*=ftag]");
									if(filter.length == 0) {
										filter = form.find("select[name*=taga]");
									}
									if(typeof filter.attr("name") === 'undefined') {
										return;
									}
									
									filter.find("option").not(".empty").each(function(k) {
										var form_val = jQuery(this).text();
										if(
											jQuery.inArray(form_val, field.values) > -1 
											|| 
											(
												((target.value.length == 0 && selected_count_current == init_selected_count)
												||
												jQuery(target).attr("name") == jQuery(initbox).attr("name")
												)
												&& 
												(jQuery(initbox).attr("name").indexOf("ftag") > -1 
												|| jQuery(initbox).attr("name").indexOf("taga") > -1) 
											)
										) {
											jQuery(this).show();
											if(filter.next().attr("type") == "button") {
												filter.multiselect("widget").find(".ui-multiselect-checkboxes li").eq(k).show();
											}
										}
										else {
											if(jQuery(target).attr("name") != filter.attr("name")) {
												jQuery(this).hide();
												if(filter.next().attr("type") == "button") {
													filter.multiselect("widget").find(".ui-multiselect-checkboxes li").eq(k).hide();
												}
											}
										}
									});	
								}
								else {
									var filter = form.find("select[name=searchword"+field.id+"]");
									if(filter.length == 0) {
										filter = form.find("select[name=searchword"+field.id+"\\[\\]]");
									}
									if(filter.length == 0) {
										filter = form.find("select[name=array"+field.id+"\\[\\]]");
									}
									
									if(typeof filter.attr("name") === 'undefined') {
										return;
									}
									
									//do not touch initial box 
									if(filter.attr("name") == jQuery(initbox).attr("name")
										&& jQuery(initbox).find(":selected").val() != ""
									) {
										return;
									}
									
									filter[0].selectize.clearOptions();
									jQuery.each(field.values, function(k, val) {
										filter[0].selectize.addOption({value: val, text: val});
									});	
									filter[0].selectize.refreshOptions(false);
								}
							});
						}
						else {
							form.find("select").each(function() {
								if(jQuery(target).attr("name") == jQuery(this).attr("name")) return;
								jQuery(this).find("option").not(".empty").hide();
								if(jQuery(this).next().attr("type") == "button") {
									jQuery(this).multiselect("widget").find(".ui-multiselect-checkboxes li").hide();
								}
							});
						}
						form.find(".dynoloader").hide();
					}
				});
			}
		</script>