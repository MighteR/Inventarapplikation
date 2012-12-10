<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/views/template/css/select2.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/select2.min.js'); ?>"></script>
<script type="text/javascript">
    
    $(document).ready(function(){
    $('button').button();
    //predefine_user_search('');
	
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
                return false; 
        }
    });
    
    $("input[id*='search']").keypress(function(e){
        if(e.which == 13){
            search_inventory();
            $(this).focus();
            var val = $(this).val();        
            $(this).val(''); 
            $(this).val(val);
        }
    });
    
    $('#show_inventory').click(function(){
        inventory('show');
    });
    
    $('#change_inventory').click(function(){
        inventory('change');
    });
    
    
    $('#search_category').select2({
        initSelection : function (element, callback) {
            callback(<?php echo $inventory_category; ?>);
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
});

function inventory(type){
    var search_category = $('#search_category').val();

    $('#loader').dialog({
            closeOnEscape: false,
            dialogClass: 'loader',
            height: 50,
            resizable: false,
            width: 50
    });

    $.ajax({
        complete: function(html){
            $('#loader').dialog('close');
        },
        url: '<?php echo current_url(); ?>/indexList',
        type: 'POST',
        data: {
            'category': search_category
        },
        success: function(html){
            $('#inventory').html(html);
        }
    });
}
    
</script>
<div id="content_title">
    <span><?php echo lang('title_inventory'); ?></span>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_category','search_category'); ?>:
    </div>
    <div class="text_right">
        <input name="search_category" id="search_category" style="width:300px;" type="hidden" value="0"/>
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="show_inventory" type="button" id="show_inventory" ><?php echo lang('title_show'); ?></button>
        <button name="update_inventory" type="button" id="update_inventory" ><?php echo lang('title_update'); ?></button>
    </div>
</div>
<div id="inventory"></div>
