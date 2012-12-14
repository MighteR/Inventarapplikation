<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){

});
//]]>    
    
</script>
<?php if($entry): ?>
<div class="text_title">
    <div style="float:left; width: 10%;">
        <?php echo lang('title_product'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_unit'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_quantity'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_price_per_unit'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_sum'); ?>
    </div>
    <div style="float:left; width: 10%;">
       <?php echo lang('title_package_type'); ?>
    </div>
    <div style="float:left; width: 10%;">
        <?php echo lang('title_quantity'); ?>
    </div>
    <div style="float:left; width: 20%;">
       <?php echo lang('title_price_per_package'); ?>
    </div>
    <div style="float:left;">
       <?php echo lang('title_sum'); ?>
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
        <?php echo $product->unit_name; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatNumber($product->unit_quantity); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo 'CHF '.formatNumber($product->unit_price); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo 'CHF '.formatNumber($product->unit_quantity * $product->unit_price); ?>
    </div>
<?php if($product->package_id != NULL): ?>
    <div style="float:left; width: 10%">
        <b><?php echo $product->package_name; ?></b>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatNumber($product->package_quantity); ?>
    </div>
    <div style="float:left; width: 20%">
        <?php echo 'CHF '.formatNumber($product->package_price); ?>
    </div>
    <div style="float:right;padding-right: 5px;">
        <?php echo 'CHF '.formatNumber($product->package_quantity * $product->package_price); ?>
    </div>
<?php endif; ?>
</div>
<?php endforeach; ?>
<div class="text_title">
    <div style="float:right;padding-right: 5px;">
    <?php echo lang('title_total').': CHF '.formatNumber($total_price); ?>
    </div>
</div>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>