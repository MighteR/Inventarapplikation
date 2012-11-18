<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_');
        id = id[2];
        
        var username = $('#username_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_user'); ?>');
        
        $('#yesno').dialog({
            closeOnEscape: false,
            height: 120,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_user'); ?>: ' + username,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url(); ?>user/delete',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(data){
                            search_user();
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
        <?php echo $this->pages->get_links('users','search_user'); ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 95%;">
        <?php echo lang('title_username'); ?>
    </div>
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($users as $user): ?>
<div id="user_<?php echo $user->id; ?>" class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:95%;">
        <a href="<?php echo base_url().'user/modify/'.$user->id; ?>"><span id="username_<?php echo $user->id; ?>"><?php echo $user->username; ?></span></a>
    </div>
    <div style="float:left;">
        <img alt="delete" id="delete_user_<?php echo $user->id; ?>" name="delete" src="<?php echo base_url(); ?>application/views/template/images/trash.png" style="cursor:pointer;" />
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>