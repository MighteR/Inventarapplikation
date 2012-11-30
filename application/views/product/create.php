<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo $this->lang->line('notice_unsaved_data') ?>';

    $(window).bind('beforeunload', function(e){
        if (changed) return sMessage;
    });
    
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
            return false; 
        }
    });

    $("input[type='text'], select, textarea").change(function(){
        changed = true;
    });
    
    $('#form').submit(function(){
        changed = false;
        $('input:disabled, select:disabled').each(function(i){
            this.disabled = false;
        });

        $("input[type='submit']").each(function(i){
            this.disabled = true;
        });
    });
    
    $( "#package_type" ).combobox();
    $( "#toggle" ).click(function(){
        $( "#package_type" ).toggle();
    });
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
	<span><?php echo lang('title_create_product'); ?></span>
</div>
<?php echo form_error('name'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_name; ?>">
      <?php echo lang('title_name','name'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="name" class="formular<?php echo $error_class_name; ?>" id="name" type="text" value="<?php echo set_value('name'); ?>"/>
    </div>
</div>
<div class="second">
    <div class="text_left">
       <?php echo lang('title_package_type'); ?>:
    </div>
    <div class="text_right">
        <select class="formular" id="package_type" name="package_type">
            <option value="create" <?php echo set_select('package_type', 'create'); ?>><?php echo lang('title_create_package'); ?></option>
<?php foreach ($categories as $category): ?>
            <option value="<?php echo $category->id; ?>" <?php echo set_select('parent_category', $category->id); ?>><?php echo $category->name; ?></option>
<?php endforeach; ?>
        </select>
        <button id="toggle">Show underlying select</button>
    </div>
</div>
        
    
    
    
    
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
</form>