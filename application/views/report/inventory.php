<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/views/template/css/select2.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/select2.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $('button').button();
    
    var changed     = <?php echo $changed; ?>;
    var sMessage    ='<?php echo $this->lang->line('notice_unsaved_data') ?>';

    $(window).bind('beforeunload', function(e){
        if (changed) return sMessage;
    });
    
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
            return false; 
        }
    });
    
    $('#submit').click(function(){
        generate_report();
    });
    
    $('#reset').click(function(){
        reset();
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
    
    function generate_report(){
        alert("Submit");
    }
    
    function reset(){
        alert("Reset");
    }
    
    $('#search_category').select2({
        initSelection : function (element, callback) {
            callback(<?php echo $inventory_category; ?>);
        },
        formatSelection: function(selection){ 
            return selection.text; 
        },
        placeholder: '<?php echo lang('title_search_categories'); ?>',
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
        var inventory_category = <?php echo $inventory_category; ?>;

        if(!jQuery.isEmptyObject(inventory_category)) $("#search_category").select2("data", inventory_category);
    });
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
    <span><?php echo lang('title_generate_report'); ?></span>
</div>
<?php echo form_error('set_due_date'); ?>
<div class="first">
    <div class="text_left<?php echo $error_class_set_due_date; ?>">
        <?php echo lang('title_due_date','set_due_date'); ?><span class="important">*</span>
    </div>
    <div class="text_right">
        <input class="formular" id="set_due_date" name="set_due_date" size="50" type="text" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_category','categories'); ?><span class="important">*</span>
    </div>
    <div class="text_right">
        <input name="search_category" id="search_category" style="width:300px;" type="hidden" value="0"/>
    </div>
</div>
<div class="first">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="submit" type="button" id="submit" ><?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>
</form>