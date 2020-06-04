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
            <a href="<?php echo admin_url('admin.php?page=jsvm_state&countryid='.$_SESSION["countryid"]); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <?php
            $heading = isset(jsvehiclemanager::$_data[0]) ? __('Edit', 'js-vehicle-manager') : __('Add New', 'js-vehicle-manager');
            echo $heading . '&nbsp' . __('State', 'js-vehicle-manager');
            ?>
        </span>
        <div class="jsvehiclemanager-form-wrap">
            <form id="jsvm-vm-form" method="post" action="<?php echo admin_url("admin.php?page=jsvm_state&task=savestate"); ?>">
                <div class="jsvm_js-field-wrapper jsvm_js-row jsvm_no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 jsvm_no-padding"><?php echo __('Name', 'js-vehicle-manager'); ?><font class="jsvm_required-notifier">*</font></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 jsvm_no-padding"><?php echo JSVEHICLEMANAGERformfield::text('name', isset(jsvehiclemanager::$_data[0]->name) ? __(jsvehiclemanager::$_data[0]->name,'js-vehicle-manager') : '', array('class' => 'jsvm_inputbox jsvm_one', 'data-validation' => 'required')) ?></div>
                </div>
                <div class="jsvm_js-field-wrapper jsvm_js-row jsvm_no-margin">
                    <div class="jsvm_js-field-title js-col-lg-3 js-col-md-3 jsvm_no-padding"><?php echo __('Published', 'js-vehicle-manager'); ?></div>
                    <div class="jsvm_js-field-obj js-col-lg-9 js-col-md-9 jsvm_no-padding"><?php echo JSVEHICLEMANAGERformfield::radiobutton('enabled', array('1' => __('Yes', 'js-vehicle-manager'), '0' => __('No', 'js-vehicle-manager')), isset(jsvehiclemanager::$_data[0]->enabled) ? jsvehiclemanager::$_data[0]->enabled : 1, array('class' => 'jsvm_radiobutton')); ?></div>
                </div>
                <?php echo JSVEHICLEMANAGERformfield::hidden('id', isset(jsvehiclemanager::$_data[0]->id) ? jsvehiclemanager::$_data[0]->id : ''); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('action', 'state_savestate'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('form_request', 'jsvehiclemanager'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('_wpnonce', wp_create_nonce('save-state')); ?>
                <div class="jsvm_js-submit-container">
                    <div class="jsvm_js-button-container">
                        <a id="jsvm_form-cancel-button" href="<?php echo admin_url('admin.php?page=jsvm_state&countryid='.$_SESSION["countryid"]); ?>" ><?php echo __('Cancel', 'js-vehicle-manager'); ?></a>
                        <?php echo JSVEHICLEMANAGERformfield::submitbutton('save', __('Save','js-vehicle-manager') .' '. __('State', 'js-vehicle-manager'), array('class' => 'button')); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
