<style>
#changelog .ui-accordion-content{
    padding: 0;
}
</style>
<script type="text/javascript">
//<![CDATA[ 
$(document).ready(function(){
    <?php if($entry): ?>;
    $('#changelog').accordion({
        autoHeight: false,
        collapsible: true,
        active: false
    });
    <?php endif; ?>
});
//]]> 
</script>
<?php if($entry): ?>
<div id="changelog" style="max-height: 400px;overflow:auto;">
<?php
$timestamp_tmp  = '';
$count = 0;

foreach($logs as $log):
    if($log->timestamp != $timestamp_tmp):
        $timestamp_tmp = $log->timestamp;

        if($count++ > 0):
?>
    </div>
<?php endif; ?>
    <h3><?php echo $timestamp_tmp.' '.lang('title_by').' <b>'.$log->username.'</b>' ?></h3>
    <div>
        <p>
            <div class="text_title">
                <div style="float:left;width:25%;">
                    <?php echo lang('title_field'); ?>
                </div>
                <div style="float:left;width:30%;">
                    <?php echo lang('title_from'); ?>
                </div>
                <div style="float:left;">
                    <?php echo lang('title_to'); ?>
                </div>
            </div>
        </p>
<?php endif; ?>
        <p>
            <div class="first list">
                <?php if(!preg_match("/deleted/i", $log->field)): ?>
                <div style="float:left;width:25%;">
                    <?php echo lang('title_'.$log->field); ?>
                </div>
                <div style="float:left;width:30%;">
                    <?php echo $log->from; ?>
                </div>
                <div style="float:left;">
                    <?php echo $log->to; ?>
                </div>
                <?php else: 
                    $field = str_replace('deleted_','',$log->field);
                ?>
                <div class="important" style="float:left;">
                    <?php if($log->to == 1){
                        echo lang('title_deleted_'.$field);
                    }else{
                        echo lang('title_reactivated_'.$field);
                    }?>
                </div>
                <?php endif; ?>
            </div>
        </p>
<?php endforeach; ?>
</div>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo lang('error_no_entries'); ?>
</div>
<?php endif; ?>