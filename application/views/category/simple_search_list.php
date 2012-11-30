<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){

});
//]]> 
</script>
<div class="first">
    <div class="text" style="text-align:center;">
        <?php echo $pages; ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 20px;">
        &nbsp;
    </div>
    <div style="float:left; width: 30%;">
        <?php echo lang('title_category'); ?>
    </div>
    <div style="float:left;">
        <?php echo lang('title_parent_category'); ?>
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($categories as $category): ?>
<label for="radio_<?php echo $category->id; ?>" style="cursor:pointer;">
<div id="category_<?php echo $category->id; ?>" class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width: 20px;">
        <input type="radio" id="radio_<?php echo $category->id; ?>" name="simple_category_id" value="<?php echo $category->id; ?>" />
    </div>
    <div style="float:left; width:30%;">
        <input type="hidden" id="simple_category_name_<?php echo $category->id; ?>" value="<?php echo $category->name; ?>" />
        <?php echo $category->name; ?>
    </div>
    <div style="float:left;">
         <?php if($category->parent_id != NULL): ?>
         <?php echo $category->parent_name; ?></a>
         <?php else: ?>
            &nbsp;
         <?php endif; ?>
    </div>
</div>
</label>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>