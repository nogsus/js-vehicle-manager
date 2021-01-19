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
        $msgkey = JSVEHICLEMANAGERIncluder::getJSModel('customfield')->getMessagekey();
        JSVEHICLEMANAGERmessages::getLayoutMessage($msgkey);
        ?>
        <span class="jsvm_js-admin-title">
            <a href="<?php echo admin_url('admin.php?page=jsvehiclemanager'); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/back-icon.png" /></a>
            <?php echo __('User Fields', 'js-vehicle-manager') ?>
            <a class="jsvm_js-button-link button" href="<?php echo admin_url('admin.php?page=jsvm_fieldordering&jsvmlt=formuserfield'); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/add_icon.png" /><?php echo __('Add User Field', 'js-vehicle-manager') ?></a>
        </span>
        <div class="jsvm_page-actions js-row no-margin">
            <a class="jsvm_js-bulk-link jsvm_button jsvm_multioperation" message="<?php echo JSVEHICLEMANAGERmessages::getMSelectionEMessage(); ?>" data-for="remove" confirmmessage="<?php echo __('Are you sure to delete', 'js-vehicle-manager') .' ?'; ?>"  href="#"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/delete-icon.png" /><?php echo __('Delete', 'js-vehicle-manager') ?></a>
        </div>
        <script	type="text/javascript">
            function resetFrom() {
                jQuery("input#title").val('');
                jQuery("select#type").val('');
                jQuery("select#required").val('');
                jQuery("form#jsvehiclemanagerform").submit();
            }
        </script>
        <form class="jsvm_js-filter-form" name="jsvehiclemanagerform" id="jsvehiclemanagerform" method="post" action="<?php echo admin_url("admin.php?page=jsvm_customfield&ff=" . jsvehiclemanager::$_data['fieldfor']); ?>">
            <?php echo JSVEHICLEMANAGERformfield::text('title', jsvehiclemanager::$_data['filter']['title'], array('class' => 'jsvm_inputbox', 'placeholder' => __('Title', 'js-vehicle-manager'))); ?>
            <?php echo JSVEHICLEMANAGERformfield::select('type', JSVEHICLEMANAGERincluder::getJSModel('common')->getFeilds(), is_numeric(jsvehiclemanager::$_data['filter']['type']) ? jsvehiclemanager::$_data['filter']['type'] : __('Select type', 'js-vehicle-manager'), __('Select','js-vehicle-manager') .' '. __('Field Type', 'js-vehicle-manager'), array('class' => 'jsvm_inputbox')); ?>
            <?php echo JSVEHICLEMANAGERformfield::select('required', JSVEHICLEMANAGERincluder::getJSModel('common')->getYesNo(), is_numeric(jsvehiclemanager::$_data['filter']['required']) ? jsvehiclemanager::$_data['filter']['required'] : '', __('Select required', 'js-vehicle-manager'), array('class' => 'jsvm_inputbox')); ?>
            <?php echo JSVEHICLEMANAGERformfield::hidden('jsvm_form_search', 'jsvm_SEARCH'); ?>
            <div class="jsvm_filter-bottom-button">
                <?php echo JSVEHICLEMANAGERformfield::submitbutton('btnsubmit', __('Search', 'js-vehicle-manager'), array('class' => 'jsvm_button')); ?>
                <?php echo JSVEHICLEMANAGERformfield::button('reset', __('Reset', 'js-vehicle-manager'), array('class' => 'jsvm_button', 'onclick' => 'resetFrom();')); ?>
            </div>
        </form>
        <?php
        if (!empty(jsvehiclemanager::$_data[0])) {
            ?>
            <form id="jsvehiclemanager-list-form" method="post" action="<?php echo admin_url("admin.php?page=jsvm_customfield"); ?>">
                <table id="jsvm_js-table">
                    <thead>
                        <tr>
                            <th class="jsvm_grid"><input type="checkbox" name="selectall" id="jsvm_selectall" value=""></th>
                            <th class="jsvm_left-row"><?php echo __('Field Name', 'js-vehicle-manager'); ?></th>
                            <th><?php echo __('Field Title', 'js-vehicle-manager'); ?></th>
                            <th><?php echo __('Field Type', 'js-vehicle-manager'); ?></th>
                            <th><?php echo __('Required', 'js-vehicle-manager'); ?></th>
                            <th><?php echo __('Read Only', 'js-vehicle-manager'); ?></th>
                            <th class="jsvm_action"><?php echo __('Action', 'js-vehicle-manager'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $k = 0;
                        for ($i = 0, $n = count(jsvehiclemanager::$_data[0]); $i < $n; $i++) {
                            $row = jsvehiclemanager::$_data[0][$i];
                            $required = ($row->required == 1) ? 'yes' : 'no';
                            $requiredalt = ($row->required == 1) ? __('Required', 'js-vehicle-manager') : __('Not Required', 'js-vehicle-manager');
                            $readonly = ($row->readonly == 1) ? 'yes' : 'no';
                            $readonlyalt = ($row->readonly == 1) ? __('Required', 'js-vehicle-manager') : __('Not Required', 'js-vehicle-manager');
                            ?>
                            <tr valign="top">
                                <td class="jsvm_grid-rows">
                                    <input type="checkbox" class="jsvm_jsvm-vm-cb" id="jsvehiclemanager-cb" name="jsvm-vm-cb[]" value="<?php echo $row->id; ?>" />
                                </td>
                                <td class="jsvm_left-row"><a href="<?php echo admin_url('admin.php?page=jsvm_customfield&jsvmlt=formuserfield&jsvehiclemanagerid='.$row->id); ?>"><?php echo $row->name; ?></a></td>
                                <td><?php echo $row->title; ?></td>
                                <td><?php echo $row->type; ?></td>
                                <td><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/<?php echo $required; ?>.png" alt="<?php echo $requiredalt; ?>" /></td>
                                <td><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/<?php echo $readonly; ?>.png" alt="<?php echo $readonlyalt; ?>" /></td>
                                <td class="jsvm_action">
                                    <a href="<?php echo admin_url('admin.php?page=jsvm_customfield&jsvmlt=formuserfield&jsvehiclemanagerid='.$row->id); ?>"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/edit.png" title="<?php echo __('Edit', 'js-vehicle-manager'); ?>"></a>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=jsvm_customfield&task=remove&action=jsvmtask&jsvm-vm-cb[]='.$row->id),'delete-customfield'); ?>" onclick="return confirmdelete('<?php echo __('Are you sure to delete', 'js-vehicle-manager').' ?'; ?>');"><img src="<?php echo jsvehiclemanager::$_pluginpath; ?>includes/images/remove.png" title="<?php echo __('Delete', 'js-vehicle-manager'); ?>"></a>
                                </td>
                            </tr>
                            <?php
                            $k = 1 - $k;
                        }
                        ?>
                    </tbody>
                </table>
                <?php echo JSVEHICLEMANAGERformfield::hidden('action', 'customfield_remove'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('task', ''); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('form_request', 'jsvehiclemanager'); ?>
                <?php echo JSVEHICLEMANAGERformfield::hidden('_wpnonce', wp_create_nonce('delete-customfield')); ?>
            </form>
            <?php
            if (jsvehiclemanager::$_data[1]) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . jsvehiclemanager::$_data[1] . '</div></div>';
            }
        } else {
            echo JSVEHICLEMANAGERlayout::getNoRecordFound();
        }
        ?>
    </div>
</div>
