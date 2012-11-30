<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo $this->lang->line('notice_unsaved_data'); ?>';

    $(window).bind('beforeunload', function(e){
        if (changed) return sMessage;
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
    
    var cache = {};
    
    $('#parent_category').autocomplete({
        minLength: 2,
        select: function(event, ui){
            //$('#parent_category_id').val(ui.item.id);
        },
        source: function(request, response){
            if(request.term in cache){
                response(cache[request.term]);
                return;
            }

            $.ajax({
                url: '<?php echo base_url('category/quick_search'); ?>',
                type: 'POST',
                data: request,
                dataType: "json",
                success: function(data){
                    var datas = eval(data);
                    var matcher = new RegExp(request.term, "i");

                    if(datas.length > 0){
                        for(var i = 0; i < datas.length; i++){
                            //datas[i].label = datas[i].label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(request.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
                            datas[i].label = datas[i].label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(request.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "$1");
                        }
                        cache[request.term] = data;
                    }else{
                        //$('#parent_category_id').val(0);
                    }
                    response(data);
                }
            });
        }
    })
    
    $("img[name='parent_category_shortcut']").click(function(){
        $('#loader').dialog({
            closeOnEscape: false,
            dialogClass: 'loader',
            height: 50,
            resizable: false,
            width: 50
        });

        $.ajax({
            url: '<?php echo base_url('category/simple_search'); ?>',
            type: 'POST',
            success: function(data){
                $('#gui').html(data);
                $('#loader').dialog('close');

                $('#gui').dialog({
                    buttons: {
                        '<?php echo lang('title_apply'); ?>': function(){
                            changed = true;

                            var id = $("input[name='simple_category_id']:checked").val();

                            $('#parent_category').val($('#simple_category_name_' + id).val());

                            $('#gui').dialog('destroy');
                        },
                        '<?php echo lang('title_cancel'); ?>': function(){
                            $('#gui').dialog('destroy');
                        }
                    },
                    closeOnEscape: false,
                    modal: true,
                    resizable: false,
                    title: '<?php echo lang('title_search_category'); ?>',
                    width: 600
                });
            }
        });
    });
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
	<span><?php echo lang('title_create_category'); ?></span>
</div>
<?php echo form_error('name'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_name; ?>">
      <?php echo lang('title_category','name'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="name" class="formular<?php echo $error_class_name; ?>" id="name" type="text" value="<?php echo set_value('name'); ?>"/>
    </div>
</div>
<?php if($categories_exists): ?>
<?php echo form_error('parent_category'); ?>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_parent_category','parent_category'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="parent_category" class="formular<?php echo $error_class_parent_category; ?>" id="parent_category" type="text" value="<?php echo set_value('parent_category'); ?>"/>
        <!--<input name="parent_category_id" id="parent_category_id" type="text" value="" />/-->
        <img alt="shortcut" name="parent_category_shortcut" src="<?php echo base_url('application/views/template/images/shortcut.png'); ?>" style="cursor:pointer;" />
    </div>
</div>
<div class="first">
<?php else: ?>
<div class="second">
<?php endif; ?>
    <div class="text_left">
        <?php echo lang('title_general_report','general_report'); ?>:
    </div>
    <div class="text_right">
        <input name="general_report" class="formular" id="general_report" type="checkbox" value="1" <?php echo set_checkbox('general_report','1'); ?>/>
    </div>
</div>
<?php if($categories_exists): ?>
<div class="second">
<?php else: ?>
<div class="first">
<?php endif; ?>
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
</form>