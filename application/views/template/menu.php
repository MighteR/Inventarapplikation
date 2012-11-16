<div>
    <div class="black">  
    <ul id="menu-content" class="mega-menu">
        
        <!--Home!-->
        <li><a href="test1.html"><?php echo $menu_home; ?></a></li>
        
        <!--Inventory!-->
        <li><a href="test2.html"><?php echo $menu_inventory; ?></a></li>
        
        <!--Report!-->
        <li><a href="test3.html"><?php echo $menu_report; ?></a>
            <ul>
                <li><a href="#"><?php echo $menu_report_generate; ?></a></li>
                <li><a href="#"><?php echo $menu_report_price; ?></a></li>
            </ul>
        </li>
        
        <!--Help!-->
        <li><a href="test.html"><?php echo $menu_help; ?></a>
            <ul>
                <li><a href="#"><?php echo $menu_help_about; ?></a></li>
                <li><a href="#"><?php echo $menu_help_online; ?></a></li>
            </ul>
        </li>   
        
        <!--Admin!-->
        <li><a href="test.html"><?php echo $menu_admin; ?></a></li>
    </div>
</div>