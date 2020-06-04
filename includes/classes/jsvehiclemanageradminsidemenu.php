<?php
if (!defined('ABSPATH')) die('Restricted Access');
$c = JSVEHICLEMANAGERrequest::getVar('page',null,'jsvehiclemanager');
$layout = JSVEHICLEMANAGERrequest::getVar('jsvmlt');
$ff = JSVEHICLEMANAGERrequest::getVar('ff');
?>
<div id="jsvehiclemanageradmin-menu-links">
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvehiclemanager">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/admin.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvehiclemanager' && $layout != 'themes' || $c == 'makeanoffer' || $c == 'tellafriend' || $c == 'buyercontacttoseller') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Admin' , 'js-vehicle-manager'); ?> <img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvehiclemanager"><span class="jsvm_text"><?php echo __('Control Panel','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvehiclemanager&jsvmlt=information"><span class="jsvm_text"><?php echo __('Information' , 'js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_activitylog&jsvmlt=activitylogs"><span class="jsvm_text"><?php echo __('Activity Logs' , 'js-vehicle-manager'); ?></span></a>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_makeanoffer'); ?>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_testdrive'); ?>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_contact'); // For buyer contact with seller ?>
        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="#">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/vehicles.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_vehicle' || ($c == 'jsvm_fieldordering' && $ff == 1) || $c == 'jsvm_vehiclealert') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Vehicles' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_vehicle"><span class="jsvm_text"><?php echo __('Vehicles' , 'js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_vehicle&jsvmlt=vehiclequeue"><span class="jsvm_text"><?php echo __('Approval Queue' , 'js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_fieldordering&ff=1"><span class="jsvm_text"><?php echo __('Fields ordering' , 'js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_fieldordering&jsvmlt=searchfieldsordering&ff=1"><span class="jsvm_text"><?php echo __('Search ordering' , 'js-vehicle-manager'); ?></span></a>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_vehiclealert'); ?>
        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_configuration">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/configuration.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'config' || $layout == 'themes') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Configurations' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_configuration"><span class="jsvm_text"><?php echo __('Configurations' , 'js-vehicle-manager'); ?></span></a>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_theme'); ?>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_configuration&jsvmlt=cronjob"><span class="jsvm_text"><?php echo __('Cron Job' , 'js-vehicle-manager'); ?></span></a>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_credit' , '' , 1); ?>
        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_make">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/by-make.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_make') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Makes By Models','js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_make"><span class="jsvm_text"><?php echo __('Makes','js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_user">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/seller.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_user' || ($c == 'jsvm_fieldordering' && $ff == 2)) echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Users' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_user"><span class="jsvm_text"><?php echo __('Users','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_fieldordering&ff=2"><span class="jsvm_text"><?php echo __('Fields ordering','js-vehicle-manager'); ?></span></a>
        </div>
    </div>
    <?php do_action('jsvm_addons_admin_sidemenu_links_for_credit' , $c , 2); ?>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_vehicletype">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/vehicle-types.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_vehicletype') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Vehicle Types' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_vehicletype"><span class="jsvm_text"><?php echo __('Vehicle Types' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_premiumplugin">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/premium_addons.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_premiumplugin') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Premium Addons' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_premiumplugin"><span class="jsvm_text"><?php echo __('Install Addons' , 'js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_premiumplugin&jsvmlt=addonfeatures"><span class="jsvm_text"><?php echo __('Addons list' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_fueltypes">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/fuel-type.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_fueltypes') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Fuel Types' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_fueltypes"><span class="jsvm_text"><?php echo __('Fuel Types' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_mileages">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/mileages.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_mileages') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Mileages' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_mileages"><span class="jsvm_text"><?php echo __('Mileages' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_modelyears">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/model-year.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_modelyears') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Model years' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_modelyears"><span class="jsvm_text"><?php echo __('Model years' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_transmissions">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/transmissions.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_transmissions') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Transmissions' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_transmissions"><span class="jsvm_text"><?php echo __('Transmissions' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>

    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_cylinders">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/cylinder.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_cylinders') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Cylinders' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_cylinders"><span class="jsvm_text"><?php echo __('Cylinders' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_conditions">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/condition.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_conditions') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Conditions' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_conditions"><span class="jsvm_text"><?php echo __('Conditions' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_currency">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/currencies.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_currency') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Currencies' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_currency"><span class="jsvm_text"><?php echo __('Currencies' , 'js-vehicle-manager'); ?></span></a>

        </div>
    </div>
    <?php do_action('jsvm_addons_admin_sidemenu_links_for_reports',$c); ?>
    <?php do_action('jsvm_addons_admin_sidemenu_links_for_export',$c); ?>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_emailtemplate">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/email_tempelates.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_emailtemplate') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Email Templates','js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplatestatus"><span class="jsvm_text"><?php echo __('Options','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=nw-ve-a"><span class="jsvm_text"><?php echo __('New Vehicle Admin','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=nw-ve"><span class="jsvm_text"><?php echo __('New Vehicle','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=nw-ve-v"><span class="jsvm_text"><?php echo __('New Vehicle Visitor','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=ve-st"><span class="jsvm_text"><?php echo __('Vehicle Status','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=de-ve"><span class="jsvm_text"><?php echo __('Delete Vehicle','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=cr-pr-a"><span class="jsvm_text"><?php echo __('Credits Purchase Admin','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=cr-pr"><span class="jsvm_text"><?php echo __('Credits Purchase','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=cr-ex"><span class="jsvm_text"><?php echo __('Credits Expire','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=nw-sl"><span class="jsvm_text"><?php echo __('Register New Seller','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=ve-al"><span class="jsvm_text"><?php echo __('Vehicle Alert','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=t-a-fr"><span class="jsvm_text"><?php echo __('Tell A Friend','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=mk-a-of"><span class="jsvm_text"><?php echo __('Make An Offer','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=sc-t-dr"><span class="jsvm_text"><?php echo __('Schedule Test Drive','js-vehicle-manager'); ?></span></a>
            <a class="jsvm_js-child" href="admin.php?page=jsvm_emailtemplate&for=mg-t-sr"><span class="jsvm_text"><?php echo __('Message To Seller','js-vehicle-manager'); ?></span></a>
         </div>
    </div>
    <div class="jsvm_js-divlink">
        <a href="admin.php?page=jsvm_country">
            <img src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/countries.png'; ?>"/>
        </a>
        <a href="#" class="jsvm_js-parent <?php if($c == 'jsvm_country') echo 'jsvm_lastshown'; ?>"><span class="jsvm_text"><?php echo __('Country' , 'js-vehicle-manager'); ?><img class="jsvm_arrow" src="<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>"/></span></a>
        <div class="jsvm_js-innerlink">
            <a class="jsvm_js-child" href="admin.php?page=jsvm_country"><span class="jsvm_text"><?php echo __('Country' , 'js-vehicle-manager'); ?></span></a>
            <?php do_action('jsvm_addons_admin_sidemenu_links_for_loadaddressdata' , $c ); ?>
        </div>
    </div>

    </div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("img#jsvm_js-admin-responsive-menu-link").click(function(e){
            e.preventDefault();
            if(jQuery("div#jsvehiclemanageradmin-leftmenu").css('display') == 'none'){
                jQuery("div#jsvehiclemanageradmin-leftmenu").show();
                jQuery("div#jsvehiclemanageradmin-leftmenu").width(280);
                jQuery("div#jsvehiclemanageradmin-leftmenu").find('a.jsvm_js-parent,a.jsvm_js-parent2').show();
                jQuery('a.jsvm_js-parent.jsvm_lastshown').next().find('a.jsvm_js-child').css('display','block');
                jQuery('a.jsvm_js-parent.jsvm_lastshown').find('img.jsvm_arrow').attr("src","<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow2.png'; ?>");
                jQuery('a.jsvm_js-parent.jsvm_lastshown').find('span').css('color','#ffffff');
            }else{
                jQuery("div#jsvehiclemanageradmin-leftmenu").hide();
            }
        });
        jQuery("div#jsvehiclemanageradmin-leftmenu").hover(function(){
            jQuery(this).find('#jsvehiclemanageradmin-menu-links').width(280);
            jQuery(this).find('a.jsvm_js-parent,a.jsvm_js-parent2').show();
            jQuery('a.jsvm_js-parent.jsvm_lastshown').next().find('a.jsvm_js-child').css('display','block');
            jQuery('a.jsvm_js-parent.jsvm_lastshown').find('img.jsvm_arrow').attr("src","<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow2.png'; ?>");
            jQuery('a.jsvm_js-parent.jsvm_lastshown').find('span').css('color','#ffffff');
        },function(){
            jQuery(this).find('#jsvehiclemanageradmin-menu-links').width(65);
            jQuery(this).find('a.jsvm_js-parent,a.jsvm_js-parent2').hide();
            jQuery('a.jsvm_js-parent.jsvm_lastshown').next().find('a.jsvm_js-child').css('display','none');
            jQuery('a.jsvm_js-parent.jsvm_lastshown').find('img.jsvm_arrow').attr("src","<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>");
            jQuery('a.jsvm_js-parent.jsvm_lastshown').find('span').css('color','#acaeb2');
        });
        jQuery("a.jsvm_js-child").find('span.jsvm_text').click(function(e){
            jQuery(this).css('color','#ffffff');
        });
        jQuery("a.jsvm_js-parent").click(function(e){
            e.preventDefault();
            jQuery('a.jsvm_js-parent.jsvm_lastshown').next().find('a.jsvm_js-child').css('display','none');
            jQuery('a.jsvm_js-parent.jsvm_lastshown').find('span').css('color','#acaeb2');
            jQuery('a.jsvm_js-parent.jsvm_lastshown').find('img.jsvm_arrow').attr("src","<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow1.png'; ?>");
            jQuery('a.jsvm_js-parent.jsvm_lastshown').removeClass('jsvm_lastshown');
            jQuery(this).find('span').css('color','#ffffff');
            jQuery(this).addClass('jsvm_lastshown');
            if(jQuery(this).next().find('a.jsvm_js-child').css('display') == 'none'){
                jQuery(this).next().find('a.jsvm_js-child').css({'display':'block'},800);
                jQuery(this).find('img.jsvm_arrow').attr("src","<?php echo jsvehiclemanager::$_pluginpath.'includes/images/left-icons/arrow2.png'; ?>");
            }
        });
    });
</script>
