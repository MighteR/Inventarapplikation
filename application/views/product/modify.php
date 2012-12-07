<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/views/template/css/select2.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/select2.min.js'); ?>"></script>
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
                'type' : 'product',
                'id'   : '<?php echo $id; ?>'
            }
        });
    });
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo lang('notice_unsaved_data') ?>';

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
    

    $('#categories').select2({
        initSelection : function (element, callback) {
            callback(<?php echo $old_categories; ?>);
        },
        formatSelection: function(selection){ 
            return selection.text; 
        },
        placeholder: '<?php echo lang('title_search_categories'); ?>',
        formatNoMatches: function(term){
            return '<?php echo lang('title_no_matches_found'); ?>';
        },
        formatSearching: function(term){
            return '<?php echo lang('title_searching'); ?>';
        },
        formatLoadMore: function(page){
            return '<?php echo lang('title_loading_more_results'); ?>';
        },
        multiple: true,
        allowClear: true,
        quietMillis: 100,
        ajax: {
            url: '<?php echo base_url('category/simple_search_list'); ?>',
            type: 'POST',
            dataType: 'json',
            quietMillis: 100,
            data: function (term, page) {
                return {
                    name: term,
                    page: page
                };
            },
            results: function (data, page) {
                var more = (page * 10) < data.total;

                return {
                    results: data.results,
                    more: more
                };
            }
        }
    });
    
    $('#reset').click(function(){
        var old_categories      = <?php echo $old_categories; ?>;

        if(!jQuery.isEmptyObject(old_categories)) $("#categories").select2("data", old_categories);
    });
    
    $('#date').attr('disabled',true);
    $('#date').addClass('disabled');
    
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
	<span><?php echo lang('title_modify_product'); ?></span>
</div>
<?php echo form_error('name'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_name; ?>">
      <?php echo lang('title_product_name','name'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="name" class="formular<?php echo $error_class_name; ?>" id="name" type="text" value="<?php echo set_value('name',$old_name); ?>"/>
    </div>
</div>
<div class="second">
    <div style="float:left;width:25%;">
        <?php echo form_error('unit'); ?>
        <div class="text_left<?php echo $error_class_unit; ?>">
           <?php echo lang('title_unit','unit'); ?>:
        </div>
        <div>
            <?php echo $old_unit; ?>
        </div>
    </div>
    <div style="float:left;width:30%;">
        <?php echo form_error('unit_price'); ?>
        <div class="text_left<?php echo $error_class_unit_price; ?>">
           <?php echo lang('title_price_per_unit','unit_price'); ?><span class="important">*</span>:
        </div>
        <div>
            <input name="unit_price" class="formular<?php echo $error_class_unit_price; ?>" id="unit_price" type="text" value="<?php echo set_value('unit_price',$old_unit_price); ?>" />
        </div>
    </div>
    <div style="float:left;width:20%;">
        <?php echo form_error('unit_quantity'); ?>
        <div class="text_left<?php echo $error_class_unit_quantity; ?>">
           <?php echo lang('title_quantity','unit_quantity'); ?><span class="important">*</span>:
        </div>
        <div>
            <input name="unit_quantity" class="formular<?php echo $error_class_unit_quantity; ?>" id="unit_quantity" type="text" size="3" value="<?php echo set_value('unit_quantity',$old_unit_quantity); ?>" />
        </div>
    </div>
    <div style="float:left;width:25%;">
        <div class="text_left">
           asdfaslkfajsdflk<?php echo lang('title_update'); ?>:
        </div>
        <div>
            <input name="unit_quantity" id="date" class="formular<?php echo $error_class_unit_quantity; ?>" id="unit_quantity" type="text" size="10" value="<?php echo set_value('unit_quantity',$old_unit_quantity); ?>" />
        </div>
    </div>
    <div style="float:left;width:75%">
        &nbsp;
    </div>
    <div style="float:left;width:25%;">
        <div class="text_left">
           <?php echo lang('title_last_update'); ?>:
        </div>
        <div>
            <?php echo $last_unit_update; ?>
        </div>
    </div>
</div>
<?php echo form_error('categories'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_categories; ?>">
       <?php echo lang('title_categories','categories'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="categories" id="categories" style="width:300px;" type="hidden" value="<?php echo set_value('categories',$old_categories); ?>"/>
    </div>
</div>
<div class="second">
    <div style="float:left;width:40%;">
    <?php echo form_error('package_type'); ?>
        <div class="text_left<?php echo $error_class_package_type; ?>">
           <?php echo lang('title_package_type','package_type'); ?>:
        </div>
        <div class="text_right">
            <?php echo $old_package_type; ?>
        </div>
    </div>
    <div style="float:left;width:30%;">
        <?php echo form_error('package_price'); ?>
        <div class="text_left<?php echo $error_class_package_price; ?>">
           <?php echo lang('title_price_per_package','package_price'); ?>
        </div>
        <div>
            <input name="package_price" class="formular<?php echo $error_class_package_price; ?>" id="package_price" type="text" value="<?php echo set_value('package_price',$old_package_price); ?>" />
        </div>
    </div>
    <div style="float:left;width:30%;">
        <?php echo form_error('package_quantity'); ?>
        <div class="text_left<?php echo $error_class_package_quantity; ?>">
           <?php echo lang('title_quantity','package_quantity'); ?>
        </div>
        <div>
            <input name="package_quantity" class="formular<?php echo $error_class_package_quantity; ?>" id="package_quantity" type="text" size="3" value="<?php echo set_value('package_quantity',$old_package_quantity); ?>" />
        </div>
    </div>
</div>    
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" id="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
</form>