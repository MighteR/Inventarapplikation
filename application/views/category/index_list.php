<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_');
        id = id[2];
        
        var name = $('#category_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_category'); ?>');
        
        $('#yesno').dialog({
            closeOnEscape: false,
            height: 120,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_category'); ?>: ' + name,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('category/delete'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(data){
                            search_category();
                            /*$('#user_' + id).fadeOut(450, function(){
                                $('#user_' + id).remove();
                            });*/
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
    <div style="float:left; width: 95%;">
        <?php echo lang('title_category'); ?>
    </div>
<!--    <div style="float:left; width: 65%;">
        <?php echo lang('title_parent_category'); ?>
    </div>/!-->
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($categories as $category): ?>
<div id="category_<?php echo $category->id; ?>" class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:95%;">
        <a href="<?php echo base_url('category/modify/'.$category->id); ?>"><span id="name_<?php echo $category->id; ?>"><?php echo $category->name; ?></span></a>
    </div>
    <!--<div style="float:left; width: 65%;">
         <?php if($category->parent_id != NULL): ?>
         <a href="<?php echo base_url('category/modify/'.$category->parent_id); ?>"><span id="name_<?php echo $category->parent_id; ?>"><?php echo $category->parent_name; ?></span></a>
         <?php else: ?>
            &nbsp;
         <?php endif; ?>
    </div>/!-->
    <div style="float:left;">
        <img alt="delete" id="delete_category_<?php echo $category->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>