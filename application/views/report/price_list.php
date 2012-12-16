<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var pricename = $('#price_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_price'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_price'); ?>: ' + pricename,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('product/delete_price'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_price();
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

        var pricename = $('#price_' + id).text();

        $('#yesno').text('<?php echo lang('question_rectivate_price'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_reactivate_price'); ?>: ' + pricename,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('product/reactivate_price'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_price();
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
<div class="text_title">
    <div style="float:left; width:10%;">
        &nbsp;
    </div>
    <div style="float:left; width: 15%">
        <?php echo lang('title_last_update'); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo lang('title_quantity'); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo lang('title_price'); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo lang('title_creator'); ?>
    </div>
    <div style="float:left; width: 15%">
        <?php echo lang('title_creation_timestamp'); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo lang('title_modifier'); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo lang('title_modification_timestamp'); ?>
    </div>
    <div style="float:right;padding-right: 5px;" />
</div>
<?php $c = 0;
foreach($prices as $price): ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:10%;">
        <span id="price_<?php echo $price->id; ?>"><?php echo lang('title_'.$price->type); ?></span>            
    </div>
    <div style="float:left; width: 15%">
        <?php echo $price->timestamp; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo formatNumber($price->quantity); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo 'CHF '.formatNumber($price->price); ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo $price->creator; ?>
    </div>
    <div style="float:left; width: 15%">
        <?php echo $price->creation_timestamp; ?>
    </div>
    <div style="float:left; width: 10%">
        <?php echo $price->modifier; ?>&nbsp;
    </div>
    <div style="float:left; width: 10%">
        <?php echo $price->modification_timestamp; ?>&nbsp;
    </div>
    <div style="float:right;padding-right: 5px;">
        <?php if (!$price->deleted): ?>
        <img alt="delete" id="delete_price_<?php echo $price->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
        <?php else: ?>
        <img alt="reactivate" id="reactivate_price_<?php echo $price->id; ?>" name="reactivate" src="<?php echo base_url('application/views/template/images/reactivate.png'); ?>" style="cursor:pointer;" />
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>