<?php
$msgkey = JSVEHICLEMANAGERincluder::getJSModel('premiumplugin')->getMessagekey();
JSVEHICLEMANAGERmessages::getLayoutMessage($msgkey);
JSVEHICLEMANAGERbreadcrumbs::getBreadcrumbs();
include_once(jsvehiclemanager::$_path . 'includes/header.php'); ?>
<div id="jsvehiclemanager-content">
	<?php
	$msg = __('Page not found....!','js-vehicle-manager');
    echo JSVEHICLEMANAGERlayout::getNoRecordFound($msg);
	?>
</div>

