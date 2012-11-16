<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $('input:button').button();
    //predefine_user_search('');

    $('#add').click(function(){
            window.location.href = '<?php echo base_url(); ?>add';
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

    $('#reset_user_search').click(function(){
        predefine_user_search('');
    });
});
	
function predefine_user_search(username){
    $('#search_user_username').val(username);

    search_user();
}
function search_user(div,url){
	var search_username = $('#search_user_username').val();

	$('#loader').dialog({
		closeOnEscape: false,
		dialogClass: 'loader',
		height: 50,
		resizable: false,
		width: 50
	});

	if(typeof div === 'undefined'){
		div = 'users';
	}
	if(typeof url === 'undefined'){
		url = '{module_path}list';
	}
	
	if($('#' + div + '_output').val() == 'undefined'){
		output = 0;
	}else{
		output = $('#' + div + '_output').val();
	}

	$.ajax({
		complete: function(html){
			$('#loader').dialog('close');
		},
		url: '<?php echo base_url();?>list',
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
	<span>{title}</span>
</div>
<div class="first">
	<div class="text_left">
		{title_username}:
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
            <?php echo form_button($field_search_user); ?>
            <?php echo form_button($reset_user_search); ?>
	</div>
</div>
<div class="text_title" style="text-align:center">
	<div><input id="add" type="button" name="add" value="{title_add_user}"></div>
</div>
<div id="users"></div>