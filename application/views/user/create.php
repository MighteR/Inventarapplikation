<?php echo form_open(current_url());?>
<div id="content_title">
	<span><?php echo $title; ?></span>
</div>
<?php echo form_error('username'); ?>
<div class="first">
    <div class="text_left">
        <?php echo $title_username; ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <?php echo form_input($field_username); ?>
    </div>
</div>
<div class="second">
    <div class="text_left">
        {title_group}:
    </div>
    <div class="text_right">

    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <?php echo form_submit('submit','Abesenden');?>
    </div>
</div>
<?php echo form_close();?>