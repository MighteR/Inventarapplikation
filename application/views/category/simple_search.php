<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $('button').button();
	
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
                return false; 
        }
    });

    $("input[id*='search']").keypress(function(e){
        if(e.which == 13){
            search_category();
            $(this).focus();
            var val = $(this).val();        
            $(this).val(''); 
            $(this).val(val);
        }
    });
	
    $('#search_category').click(function(){
        search_category();
    });

    $('#reset_search_category').click(function(){
        predefine_category_search('');
    });
});
	
function predefine_category_search(name){
    $('#search_name').val(name);
 
    search_category();
}

function search_category(page){
    var search_name = $('#search_name').val();

    if(typeof page === 'undefined'){
        page = '';
    }

    $('#loader').dialog({
            closeOnEscape: false,
            dialogClass: 'loader',
            height: 50,
            resizable: false,
            width: 50
    });

    if($('#categories_output').val() == 'undefined'){
        output = 0;
    }else{
        output = $('#categories_output').val();
    }

    $.ajax({
        complete: function(){
            $('#loader').dialog('close');
        },
        url: '<?php echo base_url('category/simple_search_list'); ?>' + page,
        type: 'POST',
        data: {
            'name': search_name,
            'page_output': output
        },
        success: function(html){
            $('#categories').html(html);
        }
    });
}
//]]> 
</script>
<div id="content_title">
    <span><?php echo lang('title_category_list'); ?></span>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_category_name','search_category_name'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="search_name" name="search_name" size="50" type="text" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="submit" type="button" id="search_category" ><?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset_search_category" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>
<div id="categories"></div>