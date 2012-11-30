<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
    $(window).bind('unload', function(e){
        $.ajax({
            url: '<?php echo base_url('lock/delete'); ?>',
            type: 'POST',
            async: false,
            data: {
                'type' : 'category',
                'id'   : '<?php echo $id; ?>'
            }
        });
    });
    
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
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
	<span><?php echo lang('title_create_category'); ?></span>
</div>
<?php echo form_error('name'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_name; ?>">
      <?php echo lang('title_category','name'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="name" class="formular<?php echo $error_class_name; ?>" id="name" type="text" value="<?php echo set_value('name', $old_name); ?>"/>
    </div>
</div>
<?php if($categories_exists): ?>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_parent_category','parent_category'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <select class="formular" id="parent_category" name="parent_category">
            <option value="NULL" <?php echo set_select('parent_category', 'NULL', ($old_parent_category == NULL) ? TRUE : FALSE); ?>><?php echo lang('title_root_category'); ?></option>
<?php foreach ($categories as $category): ?>
            <option value="<?php echo $category->id; ?>" <?php echo set_select('parent_category', $category->id, ($old_parent_category == $category->id) ? TRUE : FALSE); ?>><?php echo $category->name; ?></option>
<?php endforeach; ?>
        </select>
    </div>
</div>
<div class="first">
<?php else: ?>
<div class="second">
<?php endif; ?>
    <div class="text_left">
        <?php echo lang('title_generate_report','generate_report'); ?>:
    </div>
    <div class="text_right">
        <input name="generate_report" class="formular" id="generate_report" type="checkbox" value="1" <?php echo set_checkbox('generate_report','1', $old_report); ?>/>
    </div>
</div>
<?php if($categories_exists): ?>
<div class="second">
<?php else: ?>
<div class="first">
<?php endif; ?>
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
</form>