<script type="text/javascript">
    
    
</script>
<div class="first">
    <div class="text" style="text-align:center;">
        <?php echo $this->pages->get_links('product','search_product'); ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 95%;">
        <?php echo lang('title_product'); ?>
    </div>
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($products as $product): ?>
<div id="package_type_<?php echo $product->id; ?>" class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:95%;">
        <a href="<?php echo base_url('product/modify/'.$product->id); ?>"><span id="product_<?php echo $product->id; ?>"><?php echo $product->name; ?></span></a>
    </div>
    <div style="float:left;">
        <img alt="delete" id="delete_product_<?php echo $product->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>