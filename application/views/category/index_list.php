<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var categoryname = $('#category_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_category'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_category'); ?>: ' + categoryname,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('category/delete'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_category();
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

        var categoryname = $('#category_' + id).text();

        $('#yesno').text('<?php echo lang('question_rectivate_category'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_reactivate_category'); ?>: ' + categoryname,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('category/reactivate'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_category();
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
        <?php echo $pages; ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 20%;">
        <?php echo lang('title_category'); ?>
    </div>
<!--    <div style="float:left; width: 65%;">
        <?php echo lang('title_parent_category'); ?>
    </div>/!-->
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
foreach($categories as $category): ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:20%;">
        <?php if (!$category->deleted): ?>
            <a href="<?php echo base_url('category/modify/'.$category->id); ?>"><span id="category_<?php echo $category->id; ?>"><?php echo $category->name; ?></span></a>
        <?php else: ?>
            <span id="category_<?php echo $category->id; ?>"><?php echo $category->name; ?></span>
        <?php endif; ?>            
    </div>
    <div style="float:left; width: 20%">
        <?php echo $category->creator_name; ?>
    </div>
    <div style="float:left; width: 15%">
        <?php echo $category->creation_timestamp; ?>
    </div>
    <div style="float:left; width: 20%">
        <?php echo $category->modifier_name; ?>&nbsp;
    </div>
    <div style="float:left; width: 20%">
        <?php echo $category->modification_timestamp; ?>&nbsp;
    </div>
    <div style="float:left;">
        <?php if (!$category->deleted): ?>
        <img alt="delete" id="delete_category_<?php echo $category->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
        <?php else: ?>
        <img alt="reactivate" id="reactivate_category_<?php echo $category->id; ?>" name="reactivate" src="<?php echo base_url('application/views/template/images/inventory.png'); ?>" style="cursor:pointer;" />
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>