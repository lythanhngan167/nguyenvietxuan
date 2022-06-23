/*
 * Copyright (c) 2017-2020 Aimy Extensions, Netzum Sorglos Software GmbH
 * Copyright (c) 2015-2017 Aimy Extensions, Lingua-Systems Software GmbH
 *
 * https://www.aimy-extensions.com/
 *
 * License: GNU GPLv2, see LICENSE.txt within distribution and/or
 *          https://www.aimy-extensions.com/software-license.html
 */
function AimySitemapAjaxInit(token) {var fnUpdateFlag=function() {var $this=jQuery(this);var $active=$this.parent().find('a.chzn-single');var code=$this.val();if(code&&code.match(/^..-..$/)) {$active.find('> span').css({'background':'transparent '+ 'url(../media/mod_languages/images/'+ (code.replace('-','_').toLowerCase())+ '.gif) '+ 'no-repeat 97% 50%','padding-right':'28px'});} else {$active.find('> span') .css({'padding-right':0,'background':'none'});}};jQuery('td.lang select') .bind('change',fnUpdateFlag) .each(fnUpdateFlag);var respToJson=function(jd) {if(jd&&typeof(jd)=='string') {try {jd=jQuery.parseJSON(jd);} catch(e){}} return jd;};var $post_and_handle=function(url,post_data,$e) {post_data[token]=1;jQuery.post(url,post_data,function(d) {d=respToJson(d);if(d!==null&&typeof d==='object'&&d.ok==1) {$e.parent().removeClass('alert-error');} else {$e.parent().addClass('alert-error');}}) .fail(function() {$e.parent().addClass('alert-error');});};var $get_row_id=function($td) {return $td.parent() .find('td:first') .find('input[type=checkbox]') .eq(0) .val();};var base_url='index.php?option=com_aimysitemap&task=url.';jQuery('td.state').each(function() {var $td=jQuery(this);var $a=$td.find('a:first');var id=$get_row_id($td);var state=$td.data('initial-state');if(!id||state==undefined)return;var pd={'id':id};pd[token]=1;$a.click(function(){jQuery.post(base_url+'toggle_state_ajax',pd,function(d) {d=respToJson(d);if(d!==null&&typeof d==='object'&&d.ok==1) {state=(state==1?0:1);$a.html(jQuery('<span></span>').addClass('btn btn-micro '+ 'aimy-icon-'+(state==0?'cancel':'ok')) .attr('title',$a.attr('data-title-'+ (state==0?'activate':'deactivate'))));}}) .fail(function(){});return false;});});jQuery('td.lock').each(function() {var $td=jQuery(this);var $a=$td.find('a:first');var id=$get_row_id($td);var lock=$td.data('initial-lock');if(!id||lock==undefined)return;var pd={'id':id};pd[token]=1;$a.click(function(){jQuery.post(base_url+'toggle_lock_ajax',pd,function(d) {d=respToJson(d);if(d!==null&&typeof d==='object'&&d.ok==1) {lock=(lock==1?0:1);$a.html(jQuery('<span></span>').addClass('btn btn-micro '+ 'aimy-icon-'+(lock==0?'un':'')+'lock') .attr('title',$a.attr('data-title-'+ (lock==0?'lock':'unlock'))));}}) .fail(function(){});return false;});});jQuery('td.lang select').bind('change',function() {var $this=jQuery(this);$post_and_handle(base_url+'change_lang_ajax',{'id':$get_row_id($this.parent()),'val':$this.val()},$this);});jQuery('td.priority select').bind('change',function() {var $this=jQuery(this);$post_and_handle(base_url+'change_priority_ajax',{'id':$get_row_id($this.parent()),'val':$this.val()},$this);});jQuery('td.changefreq select').bind('change',function() {var $this=jQuery(this);$post_and_handle(base_url+'change_changefreq_ajax',{'id':$get_row_id($this.parent()),'val':$this.val()},$this);});} 
