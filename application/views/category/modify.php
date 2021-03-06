<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<!--<link href="<?php echo base_url('application/views/template/css/select2.css'); ?>" rel="stylesheet" type="text/css" />/!-->
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<!--<script type="text/javascript" src="<?php echo base_url('application/views/template/js/select2.min.js'); ?>"></script>/!-->
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('input:submit, input:reset').button();
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo $this->lang->line('notice_unsaved_data') ?>';

    $(window).bind('beforeunload', function(e){
        if (changed){
            return sMessage;
        }else{
            $.ajax({
                url: '<?php echo base_url('lock/delete'); ?>',
                type: 'POST',
                async: false,
                data: {
                    'type' : 'category',
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
                'type': 'category'
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
    
    /*$('#parent_category').select2({
        initSelection : function (element, callback) {
            callback(<?php echo $old_parent_category; ?>);
        },
        formatSelection: function(selection){ 
            return selection.text; 
        },
        placeholder: '<?php echo lang('title_search_category'); ?>',
        formatNoMatches: function(term){
            return '<?php echo lang('title_no_matches_found'); ?>';
        },
        formatSearching: function(term){
            return '<?php echo lang('title_searching'); ?>';
        },
        formatLoadMore: function(page){
            return '<?php echo lang('title_loading_more_results'); ?>';
        },
        allowClear: true,
        quietMillis: 100,
        ajax: {
            url: '<?php echo base_url('category/simple_search_list'); ?>',
            type: 'POST',
            dataType: 'json',
            quietMillis: 100,
            data: function (term, page) {
                return {
                    name: term,
                    page: page
                };
            },
            results: function (data, page) {
                var more = (page * 10) < data.total;

                return {
                    results: data.results,
                    more: more
                };
            }
        }
    });
    
    $('#reset').click(function(){
        var old_parent_category = <?php echo $old_parent_category; ?>;
    
        if(!jQuery.isEmptyObject(old_parent_category)) $("#parent_category").select2("data", old_parent_category);
    });*/
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
    <span>
        <?php echo lang('title_modify_category'); ?> <img alt="changelog" name="changelog" id="changelog" src="<?php echo base_url('application/views/template/images/changelog.png'); ?>" style="cursor: pointer;"/>
    </span>
</div>
<?php echo form_error('name'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_name; ?>">
      <?php echo lang('title_category','name'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="name" class="formular<?php echo $error_class_name; ?>" id="name" type="text" value="<?php echo set_value('name', $old_name); ?>"/>
    </div>
</div>
<!--
<?php echo form_error('parent_category'); ?>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_parent_category','parent_category'); ?><span class="important">*</span>:
    </div>
    <div class="text_right">
        <input name="parent_category" id="parent_category" style="width:300px;" type="hidden" value="<?php echo set_value('parent_category', $old_parent_category_list); ?>"/>
    </div>
</div>
<div class="first">
/!-->
<div class="second">
    <div class="text_left">
        <?php echo lang('title_general_report','general_report'); ?>:
    </div>
    <div class="text_right">
        <input name="general_report" id="general_report" type="checkbox" value="1" <?php echo set_checkbox('general_report','1', $old_general_report); ?>/>
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <input name="submit" type="submit" value="<?php echo lang('title_submit'); ?>"/>
        <input name="reset" id="reset" type="reset" value="<?php echo lang('title_reset'); ?>"/>
    </div>
</div>
</form>