<?php
if (!defined('ABSPATH'))
    die('Restricted Access');
wp_enqueue_script('jsjob-res-tables', jsvehiclemanager::$_pluginpath . 'includes/js/responsivetable.js');
?>
<div id="jsvehiclemanageradmin-wrapper">
    <div id="jsvehiclemanageradmin-leftmenu">
        <?php JSVEHICLEMANAGERincluder::getClassesInclude('jsvehiclemanageradminsidemenu'); ?>
    </div>
    <div id="jsvehiclemanageradmin-data">
        <?php 
        $msgkey = JSVEHICLEMANAGERincluder::getJSModel('systemerror')->getMessagekey();
        JSVEHICLEMANAGERmessages::getLayoutMessage($msgkey); 
        ?>
        <span class="jsvm_js-admin-title">
            <a href="<?php echo admin_url('admin.php?page=jsvehiclemanager'); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <?php echo __('Error Log', 'js-vehicle-manager'); ?>
        </span>
        <?php
        if (!empty(jsvehiclemanager::$_data[0])) {
            ?>
            <table id="jsvm_js-table">
                <thead>
                    <tr>
                        <th class="jsvm_left-row"><?php echo __('Error', 'js-vehicle-manager'); ?></th>
                        <th ><?php echo __('View', 'js-vehicle-manager'); ?></th>
                        <th ><?php echo __('Date', 'js-vehicle-manager'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach (jsvehiclemanager::$_data[0] AS $systemerror) {
                        $isview = ($systemerror->isview == 1) ? 'no.png' : 'yes.png';
                        ?>
                        <tr valign="top">
                            <td class="jsvm_left-row">
                                <?php echo $systemerror->error; ?>
                            </td>
                            <td>
                                <img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/<?php echo $isview; ?>" />
                            </td>
                            <td>
                                <?php 
                                    echo date_i18n(jsvehiclemanager::$_config['date_format'], strtotime($systemerror->created)); ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <?php
            if (jsvehiclemanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jsvehiclemanager::$_data[1] . '</div></div>';
            }
        } else {
            $msg = __('No record found','js-vehicle-manager');
            echo jsvehiclemanagerlayout::getNoRecordFound($msg);
        }
        ?>
    </div>
</div>

