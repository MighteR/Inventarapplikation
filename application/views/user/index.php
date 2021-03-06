<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[ 
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
            search_user();
            $(this).focus();
            var val = $(this).val();        
            $(this).val(''); 
            $(this).val(val);
        }
    });
	
    $('#search_user').click(function(){
        search_user();
    });

    $('#reset_search_user').click(function(){
        predefine_user_search('');
    });
});
	
function predefine_user_search(username){
    $('#search_user_username').val(username);

    search_user();
}
function search_user(page){
    var search_username = $('#search_user_username').val();

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

    if($('#users_output').val() == 'undefined'){
        output = 0;
    }else{
        output = $('#users_output').val();
    }

    $.ajax({
        complete: function(html){
            $('#loader').dialog('close');
        },
        url: '<?php echo current_url(); ?>/indexList' + page,
        type: 'POST',
        data: {
            'username': search_username,
            'page_output': output
        },
        success: function(html){
            $('#users').html(html);
        }
    });
}
//]]> 
</script>
<div id="content_title">
    <span><?php echo lang('title_user_list'); ?></span>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_username','search_user_username'); ?>
    </div>
    <div class="text_right">
        <input class="formular" id="search_user_username" name="search_user_username" size="50" type="text" />
    </div>
</div>
<div class="second">
    <div class="text_left">
        &nbsp;
    </div>
    <div class="text_right">
        <button name="submit" type="button" id="search_user" ><?php echo lang('title_submit'); ?></button>
        <button name="reset" type="button" id="reset_search_user" ><?php echo lang('title_reset'); ?></button>
    </div>
</div>
<div class="text_title" style="text-align:center">
    <div><button name="create" type="button" id="create" ><?php echo lang('title_create_user'); ?></button></div>
</div>
<div id="users"></div>