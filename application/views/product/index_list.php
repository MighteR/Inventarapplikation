<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var productname = $('#product_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_product'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_product'); ?>: ' + productname,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('product/delete'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_product();
                        }
                    });
                    $('#yesno').dialog('destroy');
                },
                '<?php echo lang('title_no'); ?>': function(){
                    $('#yesno').dialog('destroy');
                }
            }
        }); 
    });
    
    $("img[name='reactivate']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var productname = $('#product_' + id).text();

        $('#yesno').text('<?php echo lang('question_rectivate_product'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_reactivate_product'); ?>: ' + productname,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('product/reactivate'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_product();
                        }
                    });
                    $('#yesno').dialog('destroy');
                },
                '<?php echo lang('title_no'); ?>': function(){
                    $('#yesno').dialog('destroy');
                }
            }
        }); 
    });
});
//]]>    
</script>
<div class="first">
    <div class="text" style="text-align:center;">
        <?php echo $this->pages->get_links('product','search_product'); ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 20%;">
        <?php echo lang('title_product'); ?>
    </div>
    <div style="float:left; width: 20%;">
        <?php echo lang('title_creator'); ?>
    </div>
    <div style="float:left; width: 15%;">
        <?php echo lang('title_creation_timestamp'); ?>
    </div>
    <div style="float:left; width: 20%;">
        <?php echo lang('title_modifier'); ?>
    </div>
    <div style="float:left; width: 15%;">
        <?php echo lang('title_modification_timestamp'); ?>
    </div>
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($products as $product): ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:20%;">
        <?php if (!$product->deleted): ?>
            <a href="<?php echo base_url('product/modify/'.$product->id); ?>"><span id="product_<?php echo $product->id; ?>"><?php echo $product->name; ?></span></a>
        <?php else: ?>
            <span id="product_<?php echo $product->id; ?>"><?php echo $product->name; ?></span>
        <?php endif; ?>            
    </div>
    <div style="float:left; width: 20%">
        <?php echo $product->creator_name; ?>
    </div>
    <div style="float:left; width: 15%">
        <?php echo $product->creation_timestamp; ?>
    </div>
    <div style="float:left; width: 20%">
        <?php echo $product->modifier_name; ?>&nbsp;
    </div>
    <div style="float:left; width: 20%">
        <?php echo $product->modification_timestamp; ?>&nbsp;
    </div>
    <div style="float:left;">
        <?php if (!$product->deleted): ?>
        <img alt="delete" id="delete_product_<?php echo $product->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
        <?php else: ?>
        <img alt="reactivate" id="reactivate_product_<?php echo $product->id; ?>" name="reactivate" src="<?php echo base_url('application/views/template/images/inventory.png'); ?>" style="cursor:pointer;" />
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>