<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $.validate();
    });
</script>
<div id="jsvehiclemanageradmin-wrapper">
    <div id="jsvehiclemanageradmin-leftmenu">
        <?php JSVEHICLEMANAGERincluder::getClassesInclude('jsvehiclemanageradminsidemenu'); ?>
    </div>
    <div id="jsvehiclemanageradmin-data">
        <span class="jsvm_js-admin-title">
            <a href="<?php echo admin_url('admin.php?page=jsvm_city&countryid='.$_SESSION["countryid"]); ?>&stateid=<?php echo $_SESSION["stateid"]; ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <?php
            $heading = isset(jsvehiclemanager::$_data[0]) ? __('Edit', 'js-vehicle-manager') : __('Add New', 'js-vehicle-manager');
            echo $heading . '&nbsp' . __('City', 'js-vehicle-manager');
            ?>
        </span>
        <?php
        $countryid = $_SESSION["countryid"];
        $stateid = $_SESSION["stateid"];
        ?>
        <div class="jsvehiclemanager-form-wrap">
            <form id="jsvehiclemanager-form" method="post" action="<?php echo admin_url("admin.php?page=jsvm_city&task=savecity&action=jsvmtask"); ?>">
                <div class="jsvm_js-field-wrapper js-row no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('State', 'js-vehicle-manager'); ?><font class="jsvm_required-notifier">*</font></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSVEHICLEMANAGERformfield::select('stateid', JSVEHICLEMANAGERincluder::getJSModel('state')->getStatesForCombo(isset(jsvehiclemanager::$_data[0]) ? jsvehiclemanager::$_data[0]->countryid : $countryid ), isset(jsvehiclemanager::$_data[0]) ? jsvehiclemanager::$_data[0]->stateid : $stateid, __('Select state','js-vehicle-manager'), array('class' => 'inputbox one')); ?></div>
                </div>
                <div class="jsvm_js-field-wrapper js-row no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('City', 'js-vehicle-manager'); ?><font class="jsvm_required-notifier">*</font></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSVEHICLEMANAGERformfield::text('name', isset(jsvehiclemanager::$_data[0]->name) ? __(jsvehiclemanager::$_data[0]->name,'js-vehicle-manager') : '', array('class' => 'inputbox one', 'data-validation' => 'required')) ?></div>
                </div>
                 <div class="jsvm_js-field-wrapper js-row no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Latitude', 'js-vehicle-manager'); ?></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSVEHICLEMANAGERformfield::text('latitude', isset(jsvehiclemanager::$_data[0]->latitude) ? __(jsvehiclemanager::$_data[0]->latitude,'js-vehicle-manager') : '', array('class' => 'inputbox one')) ?></div>
                </div>
                 <div class="jsvm_js-field-wrapper js-row no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Longitude', 'js-vehicle-manager'); ?></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSVEHICLEMANAGERformfield::text('longitude', isset(jsvehiclemanager::$_data[0]->longitude) ? __(jsvehiclemanager::$_data[0]->longitude,'js-vehicle-manager') : '', array('class' => 'inputbox one')) ?></div>
                </div>
                <div class="jsvm_js-field-wrapper js-row no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 no-padding"><?php echo __('Published', 'js-vehicle-manager'); ?></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 no-padding"><?php echo JSVEHICLEMANAGERformfield::radiobutton('enabled', array('1' => __('Yes', 'js-vehicle-manager'), '0' => __('No', 'js-vehicle-manager')), isset(jsvehiclemanager::$_data[0]->enabled) ? jsvehiclemanager::$_data[0]->enabled : 1, array('class' => 'radiobutton')); ?></div>
                </div>
                <?php echo JSVEHICLEMANAGERformfield::hidden('id', isset(jsvehiclemanager::$_data[0]->id) ? jsvehiclemanager::$_data[0]->id : ''); ?>
                <?php
                if (isset(jsvehiclemanager::$_data[0]->id) AND ( jsvehiclemanager::$_data[0]->id != 0)) {
                    echo JSVEHICLEMANAGERformfield::hidden('isedit', 1);
                }
                ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('action', 'city_savecity'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('form_request', 'jsvehiclemanager'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('_wpnonce', wp_create_nonce('save-city')); ?>
                <div class="jsvm_js-submit-container">
                    <div class="jsvm_js-button-container">
                        <a id="jsvm_form-cancel-button" href="<?php echo admin_url('admin.php?page=jsvm_city&countryid='.$_SESSION["countryid"]); ?>&stateid=<?php echo $_SESSION["stateid"]; ?>" ><?php echo __('Cancel', 'js-vehicle-manager'); ?></a>
                        <?php echo JSVEHICLEMANAGERformfield::submitbutton('save', __('Save','js-vehicle-manager') .' '. __('City', 'js-vehicle-manager'), array('class' => 'button')); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
