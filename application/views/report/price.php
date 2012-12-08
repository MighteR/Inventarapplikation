<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
</script>
<div id="content_title">
    <span><?php echo lang('title_price_trend'); ?></span>
</div>
<div class="first">
    <div style="float:left;width:40%;">
        <div class="text_left">
            <?php echo lang('title_date_from','set_date_from'); ?>
        </div>
        <div class="text_right">
            <input class="formular" id="set_date_from" name="set_date_from" size="50" type="text" />
        </div>
    </div>
    <div style="float:left;width:40%;">
        <div class="text_left">
            <?php echo lang('title_date_to','set_date_to'); ?>
        </div>
        <div class="text_right">
            <input class="formular" id="set_date_to" name="set_date_to" size="50" type="text" />
        </div>
    </div>
</div>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_product','set_product'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="set_product" name="set_product" size="50" type="text" />
    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="submit" type="button" id="generate_price_trend" ><?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset_price_trend" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>