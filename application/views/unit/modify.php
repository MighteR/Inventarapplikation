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
                    'type' : 'unit',
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
                'type': 'unit'
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
        <?php echo lang('title_modify_unit'); ?> <img alt="changelog" name="changelog" id="changelog" src="<?php echo base_url('application/views/template/images/changelog.png'); ?>" style="cursor: pointer;"/>
    </span>
</div>
<?php echo form_error('name'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_name; ?>">
        <?php echo lang('title_unit_name','name'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="name" class="formular<?php echo $error_class_name; ?>" id="name" type="text" value="<?php echo set_value('name',$old_name); ?>"/>
    </div>
</div>
<div class="second">
    <div class="text_left">
        <label for="package_type"><?php echo lang('title_package_type','package_type'); ?></label>:
    </div>
    <div class="text_right">
        <input name="package_type" id="package_type" type="checkbox" value="1" <?php echo set_checkbox('package_type','1', $old_package_type); ?>/>
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