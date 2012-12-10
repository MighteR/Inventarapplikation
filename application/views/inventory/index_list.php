<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){

});
//]]>    
    
</script>
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
    $category_name_tmp = $product->category_name;

    $total_price += $product->unit_quantity * $product->unit_price;
    
    if($product->package_id != NULL){
        $total_price += $product->package_quantity * $product->package_price;
    }
?>
<?php if($category_name_tmp != $category_name):
    $category_name = $category_name_tmp; ?>
<div class="text_title">
    <div><?php echo $category_name; ?></div>
</div>
<?php endif; ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width: 10%">
        <b><?php echo $product->product_name; ?></b>
    </div>
    <div style="float:left; width: 10%">
        <?php echo $product->unit_quantity; ?> <?php echo $product->unit_name; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatCurrency($product->unit_price); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatCurrency($product->unit_quantity * $product->unit_price); ?>
    </div>
<?php if($product->package_id != NULL): ?>
    <div style="float:left; width: 10%">
        <b><?php echo $product->package_name; ?></b>
    </div>
    <div style="float:left; width: 10%">
        <?php echo $product->package_quantity; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatCurrency($product->package_price); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatCurrency($product->package_quantity * $product->package_price); ?>
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
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>