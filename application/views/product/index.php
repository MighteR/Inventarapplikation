<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
    
    $(document).ready(function(){
    $('button').button();
    //predefine_user_search('');

    $('#create').click(function(){
            window.location.href = '<?php echo current_url(); ?>/create';
    });
	
    $(document).keypress(function(e){ 
        var element = e.target.nodeName.toLowerCase(); 
        if (e.keyCode == 13 && element != 'textarea'){ 
                return false; 
        }
    });
    
    $("input[id*='search']").keypress(function(e){
        if(e.which == 13){
            search_product();
            $(this).focus();
            var val = $(this).val();        
            $(this).val(''); 
            $(this).val(val);
        }
    });
    
    $('#search_product').click(function(){
        search_product();
    });
    
    $('#reset_search_product').click(function(){
        predefine_product_search('');
    });
});

function predefine_product_search(name){
    $('#search_name').val(name);

    search_product();
}
function search_product(page){
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

    if($('#product_output').val() == 'undefined'){
        output = 0;
    }else{
        output = $('#product_output').val();
    }

    $.ajax({
        complete: function(html){
            $('#loader').dialog('close');
        },
        url: '<?php echo current_url(); ?>/indexList' + page,
        type: 'POST',
        data: {
            'name': search_name,
            'page_output': output
        },
        success: function(html){
            $('#products').html(html);
        }
    });
}
    
</script>
<div id="content_title">
    <span><?php echo lang('title_product_list'); ?></span>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_product_name','search_product_name'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="search_name" name="search_product" size="50" type="text" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="submit" type="button" id="search_product" ><?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset_search_product" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>
<div class="text_title" style="text-align:center">
    <div><button name="create" type="button" id="create" ><?php echo lang('title_create_product'); ?></button></div>
</div>
<div id="products"></div>
