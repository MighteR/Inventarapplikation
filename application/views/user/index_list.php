<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var username = $('#user_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_user'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_user'); ?>: ' + username,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('user/delete'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_user();
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

        var username = $('#user_' + id).text();

        $('#yesno').text('<?php echo lang('question_rectivate_user'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_reactivate_user'); ?>: ' + username,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('user/reactivate'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_user();
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
        <?php echo $pages ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 20%;">
        <?php echo lang('title_username'); ?>
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
foreach($users as $user): ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:20%;">
        <?php if (!$user->deleted): ?>
            <a href="<?php echo base_url('user/modify/'.$user->id); ?>"><span id="username_<?php echo $user->id; ?>"><?php echo $user->username; ?></span></a>
        <?php else: ?>
            <span id="username_<?php echo $user->id; ?>"><?php echo $user->username; ?></span>
        <?php endif; ?>            
    </div>
    <div style="float:left; width: 20%">
        <?php echo $user->creator_name; ?>
    </div>
    <div style="float:left; width: 15%">
        <?php echo $user->creation_timestamp; ?>
    </div>
    <div style="float:left; width: 20%">
        <?php echo $user->modifier_name; ?>&nbsp;
    </div>
    <div style="float:left; width: 20%">
        <?php echo $user->modification_timestamp; ?>&nbsp;
    </div>
    <div style="float:left;">
        <?php if (!$user->deleted): ?>
        <img alt="delete" id="delete_user_<?php echo $user->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
        <?php else: ?>
        <img alt="reactivate" id="reactivate_user_<?php echo $user->id; ?>" name="reactivate" src="<?php echo base_url('application/views/template/images/inventory.png'); ?>" style="cursor:pointer;" />
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>