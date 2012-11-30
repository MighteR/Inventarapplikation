<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
    
</script>
<div id="content_title">
    <span><?php echo lang('title_product_list'); ?></span>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_product_name','search_title_product_name'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="search_name" name="search_name" size="50" type="text" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="create" type="button" id="search_package_type" ><?php echo lang('title_submit'); ?></button>
        <button name="create" type="button" id="reset_search_package_type" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>
<div class="text_title" style="text-align:center">
    <div><button name="create" type="button" id="create" ><?php echo lang('title_create_product'); ?></button></div>
</div>
<div id="package_type"></div>
