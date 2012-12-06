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
        predefine_category_search('','all');
    });
});
	
function predefine_category_search(name,category_with_child){
    $('#search_name').val(name);
    $("#search_category_with_child option[value='" + category_with_child + "']").attr('selected',true);

    search_category();
}
function search_category(page){
    var search_name = $('#search_name').val();
    var search_category_with_child = $('#search_category_with_child').val();
    var search_general_report = $("input:radio[name='search_general_report']:checked").val();

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
        complete: function(html){
            $('#loader').dialog('close');
        },
        url: '<?php echo current_url(); ?>/indexList' + page,
        type: 'POST',
        data: {
            'name': search_name,
            'category_with_child': search_category_with_child,
            'general_report': search_general_report,
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
       <?php echo lang('title_category_with_childs','search_category_with_child'); ?>
    </div>
    <div class="text_right">
        <select class="formular" id="search_category_with_child" name="search_category_with_child">
            <option value="all"><?php echo lang('title_all_categories'); ?></option>

<?php if($categories_exists): 
    foreach ($categories as $category): ?>
            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
<?php endforeach;
endif; ?>
        </select>
    </div>
</div>
<div class="first">
    <div class="text_left">
        <?php echo lang('title_general_report'); ?>
    </div>
    <div class="text_right">
        <input name="search_general_report" id="search_general_report_all" type="radio" value="all" checked="checked" /><?php echo lang('title_both', 'search_general_report_all'); ?>
        <input name="search_general_report" id="search_general_report_1" type="radio" value="1" /><?php echo lang('title_included', 'search_general_report_1'); ?>&nbsp;
        <input name="search_general_report" id="search_general_report_0" type="radio" value="0" /><?php echo lang('title_excluded', 'search_general_report_0'); ?>&nbsp;
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
<div class="text_title" style="text-align:center">
    <div><button name="create" type="button" id="create" ><?php echo lang('title_create_category'); ?></button></div>
</div>
<div id="categories"></div>