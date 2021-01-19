<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSVEHICLEMANAGERModelyearsController {

    private $_msgkey;

    function __construct() {
        $this->_msgkey = JSVEHICLEMANAGERincluder::getJSModel('modelyears')->getMessagekey();
        self::handleRequest();
    }

    function handleRequest() {
        $layout = JSVEHICLEMANAGERrequest::getLayout('jsvmlt', null, 'modelyears');
        if (self::canaddfile()) {
            switch ($layout) {
                case 'admin_modelyears':
                    JSVEHICLEMANAGERincluder::getJSModel('modelyears')->getAllModelyears();
                    break;
                case 'admin_formmodelyear':
                    $id = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanagerid');
                    JSVEHICLEMANAGERincluder::getJSModel('modelyears')->getModelyearbyId($id);
                    break;
                case 'vehiclesbymodelyear':
                    JSVEHICLEMANAGERincluder::getJSModel('modelyears')->getVehiclesModelyears();
                    break;
            }
            $module = (is_admin()) ? 'page' : 'jsvmme';
            $module = JSVEHICLEMANAGERrequest::getVar($module, null, 'modelyears');
            $module = str_replace('jsvm_', '', $module);
            JSVEHICLEMANAGERincluder::include_file($layout, $module);
        }
    }

    function canaddfile() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsvehiclemanager')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsvmtask')
            return false;
        else
            return true;
    }

    function savemodelyear() {
        if(!is_admin())
            return false;
        $nonce = JSVEHICLEMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'save-modelyear') ) {
            die( 'Security check Failed' ); 
        }
        $data = JSVEHICLEMANAGERrequest::get('post');
        $url = admin_url("admin.php?page=jsvm_modelyears&jsvmlt=modelyears");
        $result = JSVEHICLEMANAGERincluder::getJSModel('modelyears')->storeModelyear($data);
        $msg = JSVEHICLEMANAGERmessages::getMessage($result, 'modelyears');
        JSVEHICLEMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        wp_redirect($url);
        exit();
    }

    function remove() {
        if(!is_admin())
            return false;
        $nonce = JSVEHICLEMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'delete-modelyear') ) {
            die( 'Security check Failed' ); 
        }
        $ids = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanager-cb');
        $result = JSVEHICLEMANAGERincluder::getJSModel('modelyears')->deleteModelyear($ids);
        $msg = JSVEHICLEMANAGERmessages::getMessage($result, 'modelyears');
        JSVEHICLEMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jsvm_modelyears&jsvmlt=modelyears");
        wp_redirect($url);
        die();
    }

    function publish() {
        if(!is_admin())
            return false;
        $nonce = JSVEHICLEMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'publish-modelyear') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSVEHICLEMANAGERrequest::getVar('pagenum');
        $ids = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanager-cb');
        $result = JSVEHICLEMANAGERincluder::getJSModel('modelyears')->publishUnpublish($ids, 1); //  for publish
        $msg = JSVEHICLEMANAGERmessages::getMessage($result, 'record');
        JSVEHICLEMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jsvm_modelyears&jsvmlt=modelyears");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

    function unpublish() {
        if(!is_admin())
            return false;
        $nonce = JSVEHICLEMANAGERrequest::getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'unpublish-modelyear') ) {
            die( 'Security check Failed' ); 
        }
        $pagenum = JSVEHICLEMANAGERrequest::getVar('pagenum');
        $ids = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanager-cb');
        $result = JSVEHICLEMANAGERincluder::getJSModel('modelyears')->publishUnpublish($ids, 0); //  for unpublish
        $msg = JSVEHICLEMANAGERmessages::getMessage($result, 'record');
        JSVEHICLEMANAGERmessages::setLayoutMessage($msg['message'], $msg['status'], $this->_msgkey);
        $url = admin_url("admin.php?page=jsvm_modelyears&jsvmlt=modelyears");
        if ($pagenum)
            $url .= "&pagenum=" . $pagenum;
        wp_redirect($url);
        die();
    }

}

$JSVEHICLEMANAGERModelyearsController = new JSVEHICLEMANAGERModelyearsController();
?>
