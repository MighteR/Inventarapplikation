<div>
    <div class="black">  
    <ul id="menu-content" class="mega-menu">
              
        <!--Home!-->
        <li><a href="<?php echo base_url(); ?>"><?php echo lang('menu_home'); ?></a></li>
        
        <?php if($logged_in): ?>
        <!--Inventory!-->
        <li><a href="<?php echo base_url('inventory'); ?>"><?php echo lang('menu_inventory'); ?></a></li>
        
        <!--Report!-->
        <li><a href="#"><?php echo lang('menu_report'); ?></a>
            <ul>
                <li><a href="<?php echo base_url('report/inventory'); ?>"><?php echo lang('menu_report_generate'); ?></a></li>
                <li><a href="<?php echo base_url('report/price'); ?>"><?php echo lang('menu_report_price'); ?></a></li>
            </ul>
        </li>
        
        <?php if($admin): ?>
        <!--Admin!-->
        <li><a href="#"><?php echo lang('menu_admin'); ?></a>
            <ul>
                <li><a href="<?php echo base_url('user'); ?>"><?php echo lang('menu_admin_user'); ?></a></li>
                <li><a href="<?php echo base_url('category'); ?>"><?php echo lang('menu_admin_category'); ?></a></li>
                <li><a href="<?php echo base_url('unit'); ?>"><?php echo lang('menu_admin_unit'); ?></a></li>
                <li><a href="<?php echo base_url('product'); ?>"><?php echo lang('menu_admin_product'); ?></a></li>
            </ul>        
        </li>
        <?php endif; ?>
        <?php endif; ?>
        
        <!--Help!-->
        <li><a href="#"><?php echo lang('menu_help'); ?></a>
            <ul>
                <li><a href="<?php echo base_url('help'); ?>"><?php echo lang('menu_help_about'); ?></a></li>
                <li><a href="#"><?php echo lang('menu_help_online'); ?></a></li>
            </ul>
        </li>
        <?php if($logged_in): ?>
        <li><a href="<?php echo base_url('user/logout'); ?>"><?php echo lang('menu_logout'); ?></a></li>
        <?php endif; ?>
    </div>
</div>