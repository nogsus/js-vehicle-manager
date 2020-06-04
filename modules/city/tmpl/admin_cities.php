<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<script type="text/javascript">
    function resetFrom() {
        document.getElementById('searchname').value = '';
        document.getElementById('status').value = '';
        document.getElementById('jsvehiclemanagerform').submit();
    }
</script>
<?php wp_enqueue_script('jsauto-res-tables', jsvehiclemanager::$_pluginpath . 'includes/js/responsivetable.js'); ?>
<?php 
$msgkey = JSVEHICLEMANAGERincluder::getJSModel('city')->getMessagekey();
JSVEHICLEMANAGERmessages::getLayoutMessage($msgkey); 
?>
<div id="jsvehiclemanageradmin-wrapper">
    <div id="jsvehiclemanageradmin-leftmenu">
        <?php JSVEHICLEMANAGERincluder::getClassesInclude('jsvehiclemanageradminsidemenu'); ?>
    </div>
    <div id="jsvehiclemanageradmin-data">
        <?php
        $countryid = $_SESSION["countryid"];
        $stateid = $_SESSION["stateid"];
        ?>
        <span class="jsvm_js-admin-title">
            <a href="<?php echo admin_url('admin.php?page=jsvm_state&countryid='.$_SESSION["countryid"]); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <?php echo __('Cities', 'js-vehicle-manager') ?>
            <a class="jsvm_js-button-link button" href="<?php echo admin_url('admin.php?page=jsvm_city&jsvmlt=formcity'); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add New','js-vehicle-manager') .' '. __('Cities', 'js-vehicle-manager') ?></a>
        </span>
        <div class="jsvm_page-actions js-row no-margin">
            <a class="jsvm_js-bulk-link button jsvm_multioperation" message="<?php echo JSVEHICLEMANAGERmessages::getMSelectionEMessage(); ?>" data-for="publish" data-for-wpnonce="<?php echo wp_create_nonce("publish-city"); ?>" href="#"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/publish-icon.png" /><?php echo __('Publish', 'js-vehicle-manager') ?></a>
            <a class="jsvm_js-bulk-link button jsvm_multioperation" message="<?php echo JSVEHICLEMANAGERmessages::getMSelectionEMessage(); ?>" data-for="unpublish" data-for-wpnonce="<?php echo wp_create_nonce("unpublish-city"); ?>" href="#"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/unbuplish.png" /><?php echo __('Unpublished', 'js-vehicle-manager') ?></a>
            <a class="jsvm_js-bulk-link button jsvm_multioperation" message="<?php echo JSVEHICLEMANAGERmessages::getMSelectionEMessage(); ?>" confirmmessage="<?php echo __('Are you sure to delete','js-vehicle-manager').' ?'; ?>" data-for="removecity" href="#"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-vehicle-manager') ?></a>

        </div>
        <form class="jsvm_js-filter-form" name="jsvehiclemanagerform" id="jsvehiclemanagerform" method="post" action="<?php echo admin_url("admin.php?page=jsvm_city&jsvmlt=cities&countryid=$countryid&stateid=$stateid"); ?>">
            <?php echo JSVEHICLEMANAGERformfield::text('searchname', jsvehiclemanager::$_data['filter']['searchname'], array('class' => 'inputbox', 'placeholder' => __('Name', 'js-vehicle-manager'))); ?>
            <?php echo JSVEHICLEMANAGERformfield::select('status', JSVEHICLEMANAGERincluder::getJSModel('common')->getstatus(), is_numeric(jsvehiclemanager::$_data['filter']['status']) ? jsvehiclemanager::$_data['filter']['status'] : '', __('Select Status', 'js-vehicle-manager'), array('class' => 'inputbox')); ?>
            <?php echo JSVEHICLEMANAGERformfield::hidden('JSVEHICLEMANAGER_form_search', 'JSVEHICLEMANAGER_SEARCH'); ?>
            <?php echo JSVEHICLEMANAGERformfield::submitbutton('btnsubmit', __('Search', 'js-vehicle-manager'), array('class' => 'button')); ?>
            <?php echo JSVEHICLEMANAGERformfield::button('reset', __('Reset', 'js-vehicle-manager'), array('class' => 'button', 'onclick' => 'resetFrom();')); ?>
        </form>
        <?php
        if (!empty(jsvehiclemanager::$_data[0])) {
            ?>
            <form id="jsvehiclemanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsvm_city"); ?>">
                <table id="jsvm_js-table">
                    <thead>
                        <tr>
                            <th class="jsvm_grid"><input type="checkbox" name="selectall" id="jsvm_selectall" value=""></th>
                            <th class="jsvm_left-row"><?php echo __('Name', 'js-vehicle-manager'); ?></th>
                            <th class="jsvm_centered"><?php echo __('Published', 'js-vehicle-manager'); ?></th>
                            <th class="jsvm_action"><?php echo __('Action', 'js-vehicle-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pagenum = JSVEHICLEMANAGERrequest::getVar('pagenum', 'get', 1);
                        $pageid = ($pagenum > 1) ? '&pagenum=' . $pagenum : '';
                        for ($i = 0, $n = count(jsvehiclemanager::$_data[0]); $i < $n; $i++) {
                            $row = jsvehiclemanager::$_data[0][$i];
                            $link = admin_url('admin.php?page=jsvm_city&jsvmlt=formcity&jsvehiclemanagerid=' . $row->id);
                            ?>			
                            <tr>
                                <td class="jsvm_grid-rows">
                                    <input type="checkbox" class="jsvehiclemanager-cb" id="jsvehiclemanager-cb" name="jsvehiclemanager-cb[]" value="<?php echo $row->id; ?>" />
                                </td>
                                <td class="jsvm_left-row">
                                    <a href="<?php echo $link; ?>">
                                        <?php echo __($row->name,'js-vehicle-manager'); ?></a>
                                </td>
                                <td width="20%">
                                    <?php if ($row->enabled == '1') { ?>
                                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsvm_city&task=unpublish&action=jsvmtask&jsvehiclemanager-cb[]='.$row->id.$pageid),'unpublish-city'); ?>">
                                            <img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/yes.png" alt="Default" border="0" />
                                        </a>
                                    <?php } else { ?>
                                        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsvm_city&task=publish&action=jsvmtask&jsvehiclemanager-cb[]='.$row->id.$pageid),'publish-city'); ?>">
                                            <img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/no.png" border="0" />
                                        </a>
                                    <?php } ?>
                                </td>
                                <td class="jsvm_action" width="20%">
                                    <a href="<?php echo $link; ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-vehicle-manager'); ?>"></a>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsvm_city&task=removecity&action=jsvmtask&jsvehiclemanager-cb[]='.$row->id),'delete-city'); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-vehicle-manager').' ?'; ?>');"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-vehicle-manager'); ?>"></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php echo JSVEHICLEMANAGERformfield::hidden('action', 'city_remove'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('pagenum', ($pagenum > 1) ? $pagenum : ''); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('task', ''); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('form_request', 'jsvehiclemanager'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('_wpnonce', wp_create_nonce('delete-city')); ?>
            </form>
            <?php
            if (jsvehiclemanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jsvehiclemanager::$_data[1] . '</div></div>';
            }
        } else {
            $msg = __('No record found','js-vehicle-manager');
            $link[] = array(
                        'link' => 'admin.php?page=jsvm_city&jsvmlt=formcity',
                        'text' => __('Add New','js-vehicle-manager') .' '. __('City','js-vehicle-manager')
                    );
            echo JSVEHICLEMANAGERlayout::getNoRecordFound($msg,$link);
        }
        ?>
    </div>
</div>
