<link href="<?php echo base_url('application/views/template/css/smoothness/jquery-ui-1.9.1.custom.min.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-ui-1.9.1.custom.min.js'); ?>"></script>
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

    $("input[type='text'], select, textarea").change(function(){
        changed = true;
    });
    
    $('#submit').click(function(){
        generate_price_trend();
    });
    
    $('#reset').click(function(){
        reset();
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
    
    function generate_price_trend(){
        alert("Submit");
    }
    
    function reset(){
        alert("Reset");
    }
});
//]]>
</script>
<form id="form" action="<?php echo current_url(); ?>" method="post" accept-charset="utf-8">
<div id="content_title">
    <span><?php echo lang('title_price_trend'); ?></span>
</div>
<div class="first">
    <div style="float:left;width:40%;">
        <div class="text_left">
            <?php echo lang('title_date_from','set_date_from'); ?><span class="important">*</span>
        </div>
        <div class="text_right">
            <input class="formular" id="set_date_from" name="set_date_from" size="50" type="text" />
        </div>
    </div>
    <div style="float:left;width:40%;">
        <div class="text_left">
            <?php echo lang('title_date_to','set_date_to'); ?><span class="important">*</span>
        </div>
        <div class="text_right">
            <input class="formular" id="set_date_to" name="set_date_to" size="50" type="text" />
        </div>
    </div>
</div>
<div class="second">
    <div class="text_left">
        <?php echo lang('title_product','set_product'); ?><span class="important">*</span>
    </div>
    <div class="text_right">
        <input class="formular" id="set_product" name="set_product" size="50" type="text" />
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