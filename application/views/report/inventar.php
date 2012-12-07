<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
</script>
<div id="content_title">
    <span><?php echo lang('title_generate_report'); ?></span>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_due_date','set_due_date'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="set_due_date" name="set_due_date" size="50" type="text" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_category','set_category'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="set_category" name="set_category" size="50" type="text" />
    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="submit" type="button" id="generate_report" ><?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset_report" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>