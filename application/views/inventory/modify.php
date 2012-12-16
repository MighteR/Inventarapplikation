<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo $this->lang->line('notice_unsaved_data') ?>';

    $(window).bind('beforeunload', function(e){
        if(changed){
            return sMessage;
        }else{
            $("input[name='locked_product[]']").each(function(){
                $.ajax({
                    url: '<?php echo base_url('lock/delete'); ?>',
                    type: 'POST',
                    async: false,
                    data: {
                        'type' : 'product',
                        'id'   : $(this).val()
                    }
                });
            });
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
    
    $("input[id*='unit_quantity_'], input[id*='unit_price_'").change(function(){
        var product_id = $(this).attr('name');
        product_id = product_id.split('_').slice(2);

        var quantity = $('#unit_quantity_' + product_id).val();
        var price = $('#unit_price_' + product_id).val();
        
        var value = quantity * price;
        
        $('#unit_total_data_' + product_id).text(value);
        $('#unit_total_text_' + product_id).text('CHF ' + formatNumber(value));
        
        calculate();

        checkDateField(product_id, 'unit');

    });
    
    $("input[id*='package_quantity_'], input[id*='package_price_'").change(function(){
        var product_id = $(this).attr('name').split('_').slice(2);

        var quantity = $('#package_quantity_' + product_id).val();
        var price = $('#package_price_' + product_id).val();
        
        var value = quantity * price;
        
        $('#package_total_data_' + product_id).text(value);
        $('#package_total_text_' + product_id).text('CHF ' + formatNumber(value));
        
        calculate();
        
        checkDateField(product_id, 'package');
    });
    
    $(".date").each(function(){
        var type = $(this).attr('name').split('_').slice(0,1);
        var product_id = $(this).attr('name').split('_').slice(3);
        
        checkDateField(product_id, type);
        
        $(this).datepicker({
            dateFormat: 'dd.mm.yy',
            altField: '#' + $(this).attr('name') + '_db',
            altFormat: "yymmdd"
        });
    });
});

function calculate(){
    var total = 0;
    
    $("div[id*='_total_data_']").each(function(){
        total += Number($(this).text());
    });

    $('#total').text('<?php echo lang('title_total').': CHF '; ?>' + formatNumber(total));
}

function formatNumber(num){
    num = Math.round(num / 0.05) * 0.05;
    
    num = isNaN(num) || num === '' || num === null ? 0.00 : num;
    
    var value = parseFloat(num).toFixed(2).split('.');
    
    value[0] = value[0].replace(/\B(?=(\d{3})+(?!\d))/g, '\'');
    
    return value.join('.');
}

function checkDateField(id, type){
    var old_price       = formatNumber($('#old_' + type + '_price_' + id).val());
    var old_quantity    = formatNumber($('#old_' + type + '_quantity_' + id).val());
    
    var new_price       = formatNumber($('#' + type + '_price_' + id).val());
    var new_quantity    = formatNumber($('#' + type + '_quantity_' + id).val());

    if(new_price != old_price || new_quantity != old_quantity){
        $('#' + type + '_update_date_' + id).attr('disabled',false);
        $('#' + type + '_update_date_' + id).removeClass('disabled');
        $('#' + type + '_update_date_' + id).val('<?php echo $actual_date; ?>');
        $('#' + type + '_update_date_' + id + '_db').val('<?php echo $actual_date_db; ?>');
    }else{
        $('#' + type + '_update_date_' + id).attr('disabled',true);
        $('#' + type + '_update_date_' + id).addClass('disabled');
        $('#' + type + '_update_date_' + id).val('');
        $('#' + type + '_update_date_' + id + '_db').val('');
    }
}
//]]>    
    
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
    <span><?php echo lang('title_modify_inventory'); ?></span>
</div>
<?php if($entry): ?>
<div class="text_title">
    <div style="float:left; width: 10%;">
        <?php echo lang('title_product'); ?>
    </div>
    <div style="float:left; width: 5%;">
       <?php echo lang('title_unit'); ?>
    </div>
    <div style="float:left; width: 5%;">
       <?php echo lang('title_quantity'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_price_per_unit'); ?>
    </div>
    <div style="float:left; width: 10%;">
       &nbsp;
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_sum'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_package_type'); ?>
    </div>
    <div style="float:left; width: 5%;">
        <?php echo lang('title_quantity'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_price_per_package'); ?>
    </div>
    <div style="float:left; width: 10%;">
       &nbsp;
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_sum'); ?>
    </div>
</div>
<?php $c = 0;
$total_price = 0;
$category_name = '';
$category_name_tmp = '';
foreach($inventory_list as $product):
    $product_id = $product['product_id'];
    $category_name_tmp = $product['category_name'];
    
    $old_unit_quantity = (!isset($product['old_unit_quantity'])) ? $product['unit_quantity'] : $product['old_unit_quantity'];
    $old_unit_price = (!isset($product['old_unit_price'])) ? $product['unit_price'] : $product['old_unit_price'];
    $old_package_quantity = (!isset($product['old_package_quantity'])) ? $product['package_quantity'] : $product['old_package_quantity'];
    $old_package_price = (!isset($product['old_package_price'])) ? $product['package_price'] : $product['old_package_price'];

    $total_price += $product['unit_quantity'] * $product['unit_price'];
    
    if($product['package_id'] != NULL){
        $total_price += $product['package_quantity'] * $product['package_price'];
    }
?>
<?php if($category_name_tmp != $category_name):
    $category_name = $category_name_tmp; ?>
<div class="text_title">
    <div><?php echo $category_name; ?></div>
</div>
<?php endif; ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <input name="category_name_<?php echo $product_id; ?>" type="hidden" value="<?php echo $category_name_tmp; ?>" />
    <input name="product_id[]" type="hidden" value="<?php echo $product_id; ?>" />
    <input name="product_name_<?php echo $product_id; ?>" type="hidden" value="<?php echo $product['product_name']; ?>" />
    <input name="unit_id_<?php echo $product_id; ?>" type="hidden" value="<?php echo $product['unit_id']; ?>" />
    <input name="unit_name_<?php echo $product_id; ?>" type="hidden" value="<?php echo $product['unit_name']; ?>" />
    <input name="old_unit_quantity_<?php echo $product_id; ?>" id="old_unit_quantity_<?php echo $product_id; ?>" type="hidden" value="<?php echo $old_unit_quantity; ?>" />
    <input name="old_unit_price_<?php echo $product_id; ?>" id="old_unit_price_<?php echo $product_id; ?>" type="hidden" value="<?php echo $old_unit_price; ?>" />
    <input name="package_id_<?php echo $product_id; ?>" type="hidden" value="<?php echo $product['package_id']; ?>" />
    <input name="package_name_<?php echo $product_id; ?>" type="hidden" value="<?php echo $product['package_name']; ?>" />
    <input name="old_package_quantity_<?php echo $product_id; ?>" id="old_package_quantity_<?php echo $product_id; ?>" type="hidden" value="<?php echo $old_package_quantity; ?>" />
    <input name="old_package_price_<?php echo $product_id; ?>" id="old_package_price_<?php echo $product_id; ?>" type="hidden" value="<?php echo $old_package_price; ?>" />

    <?php if(isset($locked[$product_id])):
    echo '<div class="notice">'.$locked[$product_id].'</div>';
    else: ?>
    <input name="locked_product[]" type="hidden" value="<?php echo $product_id; ?>" />
    <?php endif; ?>
    <?php echo form_error('unit_quantity_'.$product_id); ?>
    <?php echo form_error('unit_price_'.$product_id); ?>
    <?php if($product['package_id'] != NULL) echo form_error('package_quantity_'.$product_id); ?>
    <?php if($product['package_id'] != NULL) echo form_error('package_price_'.$product_id); ?>
    <div style="float:left; width: 10%">
        <b><?php echo $product['product_name']; ?></b>
    </div>
    <div style="float:left; width: 5%">
        <?php echo $product['unit_name']; ?>
    </div>
    <div style="float:left; width: 5%">
        <?php if(!isset($locked[$product_id])): ?>
        <input name="unit_quantity_<?php echo $product_id; ?>" id="unit_quantity_<?php echo $product_id; ?>" class="formular<?php echo ${'error_class_unit_quantity_'.$product_id}; ?>" type="text" size="5" value="<?php echo formatNumber($product['unit_quantity']); ?>"/>
        <?php else: ?>
        <input name="unit_quantity_<?php echo $product_id; ?>" id="unit_quantity_<?php echo $product_id; ?>" type="hidden" value="<?php echo formatNumber($product['unit_quantity']); ?>"/>
        <?php echo formatNumber($product['unit_quantity']); ?>
        <?php endif; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php if(!isset($locked[$product_id])): ?>
        / <input name="unit_price_<?php echo $product_id; ?>" id="unit_price_<?php echo $product_id; ?>" class="formular<?php echo ${'error_class_unit_price_'.$product_id}; ?>" type="text" size="5" value="<?php echo formatNumber($product['unit_price']); ?>"/>
        <?php else: ?>
        / <input name="unit_price_<?php echo $product_id; ?>" id="unit_price_<?php echo $product_id; ?>" type="hidden" value="<?php echo formatNumber($product['unit_price']); ?>"/>
        <?php echo formatNumber($product['unit_price']); ?>        
        <?php endif; ?>
    </div>
    <div style="float:left; width: 10%;">
        <?php if(!isset($locked[$product_id])): ?>
        <input name="unit_update_date_<?php echo $product_id; ?>" id="unit_update_date_<?php echo $product_id; ?>" class="formular<?php echo ${'error_class_unit_update_date_'.$product_id}; ?> date" type="text" size="10" value="<?php echo (!isset($product['unit_update_date'])) ? '' : $product['unit_update_date']; ?>" />
        <input name="unit_update_date_db_<?php echo $product_id; ?>" id="unit_update_date_<?php echo $product_id; ?>_db" type="hidden" value="<?php echo (!isset($product['unit_update_date'])) ? '' : $product['unit_update_date_db']; ?>" />
        <?php else: ?>
        <input name="unit_update_date_<?php echo $product_id; ?>" id="unit_update_date_<?php echo $product_id; ?>" type="hidden" value="<?php echo (!isset($product['unit_update_date'])) ? '' : $product['unit_update_date']; ?>" />
        <input name="unit_update_date_db_<?php echo $product_id; ?>" id="unit_update_date_<?php echo $product_id; ?>_db" type="hidden" value="<?php echo (!isset($product['unit_update_date'])) ? '' : $product['unit_update_date_db']; ?>" />
        &nbsp;
        <?php endif; ?>
    </div>
    <div style="float:left; width: 10%">
        <div id="unit_total_data_<?php echo $product_id; ?>" style="display:none">
            <?php echo $product['unit_quantity'] * $product['unit_price']; ?>
        </div>
        <span id="unit_total_text_<?php echo $product_id; ?>">
            <?php echo 'CHF '.formatNumber($product['unit_quantity'] * $product['unit_price'], true); ?>
        </span>
    </div>
<?php if($product['package_id'] != NULL): ?>
    <div style="float:left; width: 10%">
        <b><?php echo $product['package_name']; ?></b>
    </div>
    <div style="float:left; width: 5%">
        <?php if(!isset($locked[$product_id])): ?>
        <input name="package_quantity_<?php echo $product_id; ?>" id="package_quantity_<?php echo $product_id; ?>" class="formular<?php echo ${'error_class_package_quantity_'.$product_id}; ?>" type="text" size="5" value="<?php echo formatNumber($product['package_quantity']); ?>"/>
        <?php else: ?>
        <input name="package_quantity_<?php echo $product_id; ?>" id="package_quantity_<?php echo $product_id; ?>" type="hidden" value="<?php echo formatNumber($product['package_quantity']); ?>"/>
        <?php echo formatNumber($product['package_quantity']); ?>
        <?php endif; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php if(!isset($locked[$product_id])): ?>
        / <input name="package_price_<?php echo $product_id; ?>" id="package_price_<?php echo $product_id; ?>" class="formular<?php echo ${'error_class_package_price_'.$product_id}; ?>" type="text" size="5" value="<?php echo formatNumber($product['package_price']); ?>"/>
        <?php else: ?>
        / <input name="package_price_<?php echo $product_id; ?>" id="package_price_<?php echo $product_id; ?>" type="hidden" value="<?php echo formatNumber($product['package_price']); ?>"/>
        <?php echo formatNumber($product['package_price']); ?>
        <?php endif; ?>
    </div>
    <div style="float:left; width: 10%;">
        <?php if(!isset($locked[$product_id])): ?>
        <input name="package_update_date_<?php echo $product_id; ?>" id="package_update_date_<?php echo $product_id; ?>" class="formular<?php echo ${'error_class_package_update_date_'.$product_id}; ?> date" type="text" size="10" value="<?php echo (!isset($product['package_update_date'])) ? '' : $product['package_update_date']; ?>" />
        <input name="package_update_date_db_<?php echo $product_id; ?>" id="package_update_date_<?php echo $product_id; ?>_db" type="hidden" value="<?php echo (!isset($product['package_update_date'])) ? '' : $product['package_update_date_db']; ?>" />
        <?php else: ?>
        <input name="package_update_date_<?php echo $product_id; ?>" id="package_update_date_<?php echo $product_id; ?>" type="hidden" value="<?php echo (!isset($product['package_update_date'])) ? '' : $product['package_update_date']; ?>" />
        <input name="package_update_date_db_<?php echo $product_id; ?>" id="package_update_date_<?php echo $product_id; ?>_db" type="hidden" value="<?php echo (!isset($product['package_update_date'])) ? '' : $product['package_update_date_db']; ?>" />
        &nbsp;
        <?php endif; ?>
    </div>
    <div style="float:left; width: 10%">
        <div id="package_total_data_<?php echo $product_id; ?>" style="display:none">
            <?php echo $product['package_quantity'] * $product['package_price']; ?>
        </div>
        <span id="package_total_text_<?php echo $product_id; ?>">
            <?php echo 'CHF '.formatNumber($product['package_quantity'] * $product['package_price'], true); ?>
        </span>
    </div>
<?php endif; ?>
</div>
<?php endforeach; ?>
<div class="text_title">
    <div style="float:right;padding-right: 5px;">
        <span id="total">
            <?php echo lang('title_total').': CHF '.formatNumber($total_price, true); ?>
        </span>
    </div>
</div>
<div class="first">
    <div>
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" id="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>
</form>