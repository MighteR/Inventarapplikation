<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo lang('notice_unsaved_data') ?>';

    $(window).bind('beforeunload', function(e){
        if (changed){
            return sMessage;
        }else{
            $.ajax({
                url: '<?php echo base_url('lock/delete'); ?>',
                type: 'POST',
                async: false,
                data: {
                    'type' : 'user',
                    'id'   : '<?php echo $id; ?>'
                }
            });
        }
    });
    
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
            return false; 
        }
    });

    $("input[type='text'], select, textarea").change(function(){
        changed = true;
    });
    
    $('#form').submit(function(){
        changed = false;
        $('input:disabled, select:disabled').each(function(i){
            this.disabled = false;
        });

        $("input[type='submit']").each(function(i){
            this.disabled = true;
        });
    });
    
    $('#changelog').click(function(){
        $('#loader').dialog({
            closeOnEscape: false,
            dialogClass: 'loader',
            height: 50,
            resizable: false,
            width: 50
        });

        $.ajax({
            complete: function(){
                $('#loader').dialog('close');
            },
            url: '<?php echo base_url('changelog'); ?>',
            type: 'POST',
            data: {
                'id': <?php echo $id; ?>,
                'type': 'user'
            },
            success: function(html){
                $('#gui').html(html);
                
                $('#gui').dialog({
                    buttons: {
                        '<?php echo lang('title_close'); ?>': function(){
                            $('#gui').dialog('destroy');
                        }
                    },
                    modal: true,
                    resizable: false,
                    title: '<?php echo lang('title_changelogs'); ?>',
                    width: 500,
                    beforeClose: function(){
                        $('#gui').dialog('destroy');
                    }
                });
            }
        });
    });
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
    <span>
        <?php echo lang('title_modify_user'); ?> <img alt="changelog" name="changelog" id="changelog" src="<?php echo base_url('application/views/template/images/changelog.png'); ?>" style="cursor: pointer;"/>
    </span>
</div>
<?php echo form_error('username'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_username; ?>">
        <?php echo lang('title_username','username'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="username" class="formular<?php echo $error_class_username; ?>" id="username" type="text" value="<?php echo set_value('username',$old_username); ?>"/>
    </div>
</div>
<?php echo form_error('password'); ?>
<div class="second">
    <div class="text_left<?php echo $error_class_password; ?>">
        <label for="password"><?php echo lang('title_password','password'); ?></label>:
    </div>
    <div class="text_right">
        <input name="password" class="formular<?php echo $error_class_password; ?>" id="password" type="password" value=""/>
    </div>
</div>
<?php echo form_error('password_confirmation'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_password_confirmation; ?>">
        <label for="password_confirmation"><?php echo lang('title_password_confirmation','password_confirmation'); ?></label>:
    </div>
    <div class="text_right">
        <input name="password_confirmation" class="formular<?php echo $error_class_password_confirmation; ?>" id="password_confirmation" type="password" value=""/>
    </div>
</div>
<div class="second">
    <div class="text_left">
        <label for="admin"><?php echo lang('title_admin','admin'); ?></label>:
    </div>
    <div class="text_right">
        <input name="admin" id="admin" type="checkbox" value="1" <?php echo set_checkbox('admin','1', $old_admin); ?>/>
    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
</form>