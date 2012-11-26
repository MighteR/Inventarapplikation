<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_');
        id = id[3];

        var name = $('#package_type_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_package_type'); ?>');
        
        $('#yesno').dialog({
            closeOnEscape: false,
            height: 120,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_package_type'); ?>: ' + name,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('package_type/delete'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(data){
                            search_package_type();
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
        <?php echo $this->pages->get_links('package_type','search_package_type'); ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 95%;">
        <?php echo lang('title_package_type'); ?>
    </div>
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($package_types as $package_type): ?>
<div id="package_type_<?php echo $package_type->id; ?>" class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:95%;">
        <a href="<?php echo base_url('package_type/modify/'.$package_type->id); ?>"><span id="package_type_<?php echo $package_type->id; ?>"><?php echo $package_type->name; ?></span></a>
    </div>
    <div style="float:left;">
        <img alt="delete" id="delete_package_type_<?php echo $package_type->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>