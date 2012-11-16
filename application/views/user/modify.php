<link href="<?php echo base_url(); ?>application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>application/views/template/js/jquery-ui-1.9.1.custom.min.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
});
//]]>
</script>
<?php echo form_open(current_url());?>
<div id="content_title">
	<span><?php echo $title; ?></span>
</div>
<?php echo form_error('username'); ?>
<div class="first">
    <div class="text_left">
        <?php echo form_label($this->lang->line('title_username'),'username'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <?php echo form_input($field_username); ?>
    </div>
</div>
<?php echo form_error('password'); ?>
<div class="second">
    <div class="text_left">
        <?php echo form_label($this->lang->line('title_password'),'password'); ?>:
    </div>
    <div class="text_right">
        <?php echo form_password($field_password); ?>
    </div>
</div>
<?php echo form_error('password_confirmation'); ?>
<div class="first">
    <div class="text_left">
        <?php echo form_label($this->lang->line('title_password_confirmation'),'password_confirmation'); ?>:
    </div>
    <div class="text_right">
        <?php echo form_password($field_password_confirmation); ?>
    </div>
</div>
<div class="second">
    <div class="text_left">
        <?php echo form_label($this->lang->line('title_admin'),'admin'); ?>:
    </div>
    <div class="text_right">
        <?php echo form_checkbox($field_admin); ?>
    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <?php echo form_submit($field_submit); ?>&nbsp;<?php echo form_reset($field_reset); ?>
    </div>
</div>
<?php echo form_close();?>