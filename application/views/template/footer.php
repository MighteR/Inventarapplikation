<div id="footer_left">Version <?php echo $version; ?></div>
<div id="footer_right">
    <?php echo anchor('language/set/english', 'English')?>
    &nbsp;|&nbsp;
    <?php echo anchor('language/set/german', 'Deutsch')?>
</div>
<?php if($logged_in): ?>
<div style="float:right; padding-right: 5px;">
    <?php echo lang('title_hello').' '.$username; ?>, <?php echo lang('title_last_login').' '.$last_login; ?>
</div>
<?php endif; ?>