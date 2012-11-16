<div class="first">
    <div class="text" style="text-align:center;">
        <?php echo $pages; ?>&nbsp;
    </div>
</div>
<div class="text_title">
    <div style="float:left;">
        <?php echo $title_username; ?>
    </div>
</div>
<?php if ($entry): ?>
<?php $c = 0;
foreach($users as $user): ?>
<div class="<?php echo ($c++ % 2) ? 'second' : 'first'; ?> list">
    <div style="float:left;">
        <a href="<?php echo base_url().'user/modify/'.$user->id; ?>"><?php echo $user->username; ?></a>
    </div>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="first" style="text-align:center;">
    <?php echo $error_no_entries; ?>
</div>
<?php endif; ?>