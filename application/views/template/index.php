<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<title><?php echo $title; ?></title>
<meta name="encoding" content="UTF-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="css/skins/black.css" rel="stylesheet" type="text/css" />
<link href="css/dcmegamenu.css" rel="stylesheet" type="text/css" />
<link href="css/css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<script type='text/javascript' src='js/jquery.hoverIntent.minified.js'></script>
<script type='text/javascript' src='js/jquery.dcmegamenu.1.3.3.js'></script>
<script type="text/javascript">
$(document).ready(function($){
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
    <img src="{path}misc/images/loading.gif" alt="" /><br />{title_loading}
</div>
<div id="gui" style="display:none;"></div>
<div id="yesno" style="display:none;"></div>
<div id="container">
    <div id="header">
        <div id="logo"></div>
        <div id="title"><h1><?php echo $title?></h1></div>
        <div id="menu">
            <div>
            <div class="black">  
            <ul id="menu-content" class="mega-menu">
                    <li><a href="test.html">Home</a></li>
                    <li><a href="test.html">Products</a>
                            <ul>
                                    <li><a href="#">Mobile Phones &#038; Accessories</a>
                                            <ul>
                                                    <li><a href="#">Product 1</a></li>
                                                    <li><a href="#">Product 2</a></li>
                                                    <li><a href="#">Product 3</a></li>
                                            </ul>
                                    </li>
                                    <li><a href="#">Desktop</a>
                                        <ul>
                                                    <li><a href="#">Product 4</a></li>
                                                    <li><a href="#">Product 5</a></li>
                                                    <li><a href="#">Product 6</a></li>
                                                    <li><a href="#">Product 7</a></li>
                                                    <li><a href="#">Product 8</a></li>
                                                    <li><a href="#">Product 9</a></li>
                                            </ul>
                                    </li>
                                    <li><a href="#">Laptop</a>
                                        <ul>
                                                    <li><a href="#">Product 10</a></li>
                                                    <li><a href="#">Product 11</a></li>
                                                    <li><a href="#">Product 12</a></li>
                                                    <li><a href="#">Product 13</a></li>
                                            </ul>
                                    </li>
                                    <li><a href="#">Accessories</a>
                                        <ul>
                                                    <li><a href="#">Product 14</a></li>
                                                    <li><a href="#">Product 15</a></li>
                                            </ul>
                                    </li>
                                    <li><a href="#">Software</a>
                                      <ul>
                                            <li><a href="#">Product 16</a></li>
                                                    <li><a href="#">Product 17</a></li>
                                                    <li><a href="#">Product 18</a></li>
                                                    <li><a href="#">Product 19</a></li>
                                      </ul>
                                    </li>
                            </ul>
                    </li>
                    <li><a href="#">Sale</a>
                            <ul>
                                    <li><a href="#">Special Offers</a>
                    <ul>
                            <li><a href="#">Offer 1</a></li>
                            <li><a href="#">Offer 2</a></li>
                            <li><a href="#">Offer 3</a></li>
                    </ul>
                    </li>
                    <li><a href="#">Reduced Price</a>
                    <ul>
                            <li><a href="#">Offer 4</a></li>
                            <li><a href="#">Offer 5</a></li>
                            <li><a href="#">Offer 6</a></li>
                            <li><a href="#">Offer 7</a></li>
                    </ul>
            </li>
                    <li><a href="#">Clearance Items</a>
                    <ul>
                            <li><a href="#">Offer 9</a></li>

                    </ul>
            </li>
                    <li class="menu-item-129"><a href="#">Ex-Stock</a>
                    <ul>
                            <li><a href="#">Offer 10</a></li>
                            <li><a href="#">Offer 11</a></li>
                            <li><a href="#">Offer 12</a></li>
                            <li><a href="#">Offer 13</a></li>
                    </ul>
            </li>
            </ul>
            </li>
            <li><a href="#">About Us</a>
            <ul>
                    <li><a href="#">About Page 1</a></li>
                    <li><a href="#">About Page 2</a></li>

            </ul>
            </li>
            <li><a href="#">Services</a>
            <ul>
                    <li><a href="#">Service 1</a>
                    <ul>
                            <li><a href="#">Service Detail A</a></li>
                            <li><a href="#">Service Detail B</a></li>
                    </ul>
            </li>
            <li><a href="#">Service 2</a>
                    <ul>
                            <li><a href="#">Service Detail C</a></li>
                    </ul>
            </li>
                    <li><a href="#">Service 3</a>
                    <ul>
                            <li><a href="#">Service Detail D</a></li>
                            <li><a href="#">Service Detail E</a></li>
                            <li><a href="#">Service Detail F</a></li>
                    </ul>
            </li>
                    <li><a href="#">Service 4</a></li>
            </ul>
            </li>
            <li><a href="#">Contact us</a></li>
            </ul>
            </div>            
            </div>
        </div>
    </div>
    <div id="content">
        <div id="main_content">
            <?php echo $content ?>
            
<div id="content_title">
	<span>{title}</span>
</div>
<div class="first">
	<div class="text_left">
		{title_transport_number}:
	</div>
	<div class="text_right">
		<input class="formular" id="search_open_approval_transport_number" name="search_open_approval_transport_number" size="50" type="text" />
	</div>
</div>
<div class="second">
	
		<div class="text_left">
			{title_group}:
		</div>
		<div class="text_right">
			<select class="formular" id="search_open_approval_group">
				<option value="all">{title_group_all}</option>

			</select>
		</div>

</div>
<div class="first">
	<div class="text_left">
		&nbsp;
	</div>
	<div class="text_right">
		<input id="search_open_approval" name="search_open_approval" type="button" value="{title_search}" />
		<input id="reset_open_approval_search" name="reset_open_approval_search" type="button" value="{title_reset}" />
	</div>
</div>            
            
            
            
        </div>
        <div style="clear:both;"></div>
    </div>
    <div id="footer">
        <div id="footer_left">Version <?php echo $version; ?></div>
        <div id="footer_right">
            <?php echo anchor('language/set/english', 'English')?>
            &nbsp;|&nbsp;
            <?php echo anchor('language/set/german', 'Deutsch')?>
        </div>
    </div>
</div>
</body>
</html>