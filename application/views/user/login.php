<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
});
//]]>
</script>   
    
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
    <span><?php echo lang('title_login'); ?></span>
</div>
<div class="first">
    <div>
      <br /><?php echo lang('text_login_information'); ?>.<br /><br />
    </div>
</div>
<?php echo form_error('login'); ?>
<?php echo form_error('username'); ?>
<div class="second">
    <div class="text_left<?php echo $error_class_username; ?>">
      <?php echo lang('title_username','username'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="username" class="formular<?php echo $error_class_username; ?>" id="username" type="text" value="<?php echo set_value('username'); ?>"/>
    </div>
</div>
<?php echo form_error('password'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_password; ?>">
        <?php echo lang('title_password','password'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="password" class="formular<?php echo $error_class_password; ?>" id="password" type="password" value="" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
    </div>
</div>