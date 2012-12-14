<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    $("img[name='delete']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var unitname = $('#unit_' + id).text();

        $('#yesno').text('<?php echo lang('question_delete_unit'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_delete_unit'); ?>: ' + unitname,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('unit/delete'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_unit();
                        }
                    });
                    $('#yesno').dialog('destroy');
                },
                '<?php echo lang('title_no'); ?>': function(){
                    $('#yesno').dialog('destroy');
                }
            }
        }); 
    });
    
    $("img[name='reactivate']").click(function(){
        var id = $(this).attr('id').split('_').pop();

        var unitname = $('#unit_' + id).text();

        $('#yesno').text('<?php echo lang('question_rectivate_unit'); ?>');

        $('#yesno').dialog({
            closeOnEscape: false,
            modal: true,
            resizable: false,
            title: '<?php echo lang('title_reactivate_unit'); ?>: ' + unitname,
            buttons: {
                '<?php echo lang('title_yes'); ?>': function(){
                    $.ajax({
                        url: '<?php echo base_url('unit/reactivate'); ?>',
                        type: 'POST',
                        data: { 'id' : id },
                        success: function(){
                            search_unit();
                        }
                    });
                    $('#yesno').dialog('destroy');
                },
                '<?php echo lang('title_no'); ?>': function(){
                    $('#yesno').dialog('destroy');
                }
            }
        }); 
    });
});
//]]> 
</script>
<div class="first">
    <div class="text" style="text-align:center;">
        <?php echo $this->pages->get_links('unit','search_unit'); ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left; width: 20%;">
        <?php echo lang('title_unit'); ?>
    </div>
    <div style="float:left; width: 20%;">
        <?php echo lang('title_creator'); ?>
    </div>
    <div style="float:left; width: 15%;">
        <?php echo lang('title_creation_timestamp'); ?>
    </div>
    <div style="float:left; width: 20%;">
        <?php echo lang('title_modifier'); ?>
    </div>
    <div style="float:left; width: 15%;">
        <?php echo lang('title_modification_timestamp'); ?>
    </div>
    <div style="float:left;">
        &nbsp;
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($units as $unit): ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left; width:20%;">
        <?php if (!$unit->deleted): ?>
            <a href="<?php echo base_url('unit/modify/'.$unit->id); ?>"><span id="unit_<?php echo $unit->id; ?>"><?php echo $unit->name; ?></span></a>
        <?php else: ?>
            <span id="unit_<?php echo $unit->id; ?>"><?php echo $unit->name; ?></span>
        <?php endif; ?>            
    </div>
    <div style="float:left; width: 20%">
        <?php echo $unit->creator_name; ?>
    </div>
    <div style="float:left; width: 15%">
        <?php echo $unit->creation_timestamp; ?>
    </div>
    <div style="float:left; width: 20%">
        <?php echo $unit->modifier_name; ?>&nbsp;
    </div>
    <div style="float:left; width: 20%">
        <?php echo $unit->modification_timestamp; ?>&nbsp;
    </div>
    <div style="float:left;">
        <?php if (!$unit->deleted): ?>
        <img alt="delete" id="delete_unit_<?php echo $unit->id; ?>" name="delete" src="<?php echo base_url('application/views/template/images/trash.png'); ?>" style="cursor:pointer;" />
        <?php else: ?>
        <img alt="reactivate" id="reactivate_unit_<?php echo $unit->id; ?>" name="reactivate" src="<?php echo base_url('application/views/template/images/inventory.png'); ?>" style="cursor:pointer;" />
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>