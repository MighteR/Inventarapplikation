<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
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
    <span><?php echo lang('title_modify_inventory'); ?></span>
</div>
<?php if($entry): ?>
<div class="text_title">
    <div style="float:left; width: 95%;">
        <?php echo lang('title_product'); ?>
    </div>
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php $c = 0;
$total_price = 0;
$category_name = '';
$category_name_tmp = '';
foreach($inventory_list as $product): 
    $category_name_tmp = $product['category_name'];

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
    <?php form_error('unit_quantity[]'); ?>

    <input name="category_name[]" type="hidden" value="<?php echo $product['category_name']; ?>" />
    <input name="product_id[]" type="hidden" value="<?php echo $product['product_id']; ?>" />
    <input name="product_name[]" type="hidden" value="<?php echo $product['product_name']; ?>" />
    <input name="unit_id[]" type="hidden" value="<?php echo $product['unit_id']; ?>" />
    <input name="unit_name[]" type="hidden" value="<?php echo $product['unit_name']; ?>" />
    <input name="package_id[]" type="hidden" value="<?php echo $product['package_id']; ?>" />
    <input name="package_name[]" type="hidden" value="<?php echo $product['package_name']; ?>" />
    
    <div style="float:left; width: 10%">
        <b><?php echo $product['product_name']; ?></b>
    </div>
    <div style="float:left; width: 10%">
        <?php echo $product['unit_name']; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo form_error('unit_quantity[]'); ?>
        <input name="unit_quantity[]" class="formular" type="text" size="3" value="<?php echo formatCurrency($product['unit_quantity']); ?>"/>
    </div>
    <div style="float:left; width: 10%">
        <input name="unit_price[]" class="formular" type="text" size="3" value="<?php echo formatCurrency($product['unit_price']); ?>"/>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatCurrency($product['unit_quantity'] * $product['unit_price']); ?>
    </div>
<?php if($product['package_id'] != NULL): ?>
    <div style="float:left; width: 10%">
        <b><?php echo $product['package_name']; ?></b>
    </div>
    <div style="float:left; width: 10%">
       <input name="package_quantity[]" class="formular" type="text" size="3" value="<?php echo formatCurrency($product['package_quantity']); ?>"/>
    </div>
    <div style="float:left; width: 10%">
       <input name="package_price[]" class="formular" type="text" size="3" value="<?php echo formatCurrency($product['package_price']); ?>"/>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatCurrency($product['package_quantity'] * $product['package_price']); ?>
    </div>
<?php endif; ?>
</div>
<?php endforeach; ?>
<div class="text_title">
    <div style="float:left;width:90%;">&nbsp;</div>
    <div style="float:left;">
    <?php echo lang('title_total').': CHF '.formatCurrency($total_price); ?>
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