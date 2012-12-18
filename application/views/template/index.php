<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta charset="utf-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="<?php echo base_url('application/views/template/css/skins/black.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/views/template/css/dcmegamenu.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('application/views/template/css/css.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('application/views/template/js/jquery-1.8.2.min.js'); ?>"></script>
<script type='text/javascript' src="<?php echo base_url('application/views/template/js/jquery.hoverIntent.minified.js'); ?>"></script>
<script type='text/javascript' src="<?php echo base_url('application/views/template/js/jquery.dcmegamenu.1.3.3.min.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#menu-content').dcMegaMenu({
		rowItems: '3',
		speed: 'fast',
		effect: 'fade'
	});
});
</script>
</head>
<body>
<div id="loader" style="display:none; text-align:center;">
    <img src="<?php echo base_url('application/views/template/images/loading.gif'); ?>" alt="" /><br /><?php echo $title_loader; ?>
</div>
<div id="gui" style="display:none;"></div>
<div id="yesno" style="display:none;"></div>
<div id="container">
    <div id="header">
        <div id="logo"></div>
        <div id="title"><h1><?php echo $title; ?></h1></div>
        <div id="menu">
            <?php echo $menu; ?>
        </div>
    </div>
    <div id="content">
        <div id="main_content">
            <?php echo $content; ?>
        </div>
    </div>
    <div id="footer">
        <?php echo $footer; ?>
    </div>
    <div style="clear:both" />
</div>
</body>
</html>