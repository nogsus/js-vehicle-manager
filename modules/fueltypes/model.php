<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSVEHICLEMANAGERfueltypesModel {

    function getFueltypebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT * FROM `#__js_vehiclemanager_fueltypes` WHERE id = " . $id;
        $db->setQuery($query);
        jsvehiclemanager::$_data[0] = $db->loadObject();
        return;
    }

    function getFuelTypeTitlebyId($id) {
        if (is_numeric($id) == false)
            return false;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT title FROM `#__js_vehiclemanager_fueltypes` WHERE id = " . $id;
        $db->setQuery($query);
        return $db->loadResult();
        
    }

    function getAllFueltypes() {
        // Filter
        $title = JSVEHICLEMANAGERrequest::getVar('title');
        $status = JSVEHICLEMANAGERrequest::getVar('status');
        $formsearch = JSVEHICLEMANAGERrequest::getVar('JSVEHICLEMANAGER_form_search', 'post');
        if ($formsearch == 'JSVEHICLEMANAGER_SEARCH') {
            $_SESSION['JSVEHICLEMANAGER_SEARCH']['title'] = $title;
            $_SESSION['JSVEHICLEMANAGER_SEARCH']['status'] = $status;
        }
        if (JSVEHICLEMANAGERrequest::getVar('pagenum', 'get', null) != null) {
            $title = (isset($_SESSION['JSVEHICLEMANAGER_SEARCH']['title']) && $_SESSION['JSVEHICLEMANAGER_SEARCH']['title'] != '') ? $_SESSION['JSVEHICLEMANAGER_SEARCH']['title'] : null;
            $status = (isset($_SESSION['JSVEHICLEMANAGER_SEARCH']['status']) && $_SESSION['JSVEHICLEMANAGER_SEARCH']['status'] != '') ? $_SESSION['JSVEHICLEMANAGER_SEARCH']['status'] : null;
        } elseif ($formsearch !== 'JSVEHICLEMANAGER_SEARCH') {
            unset($_SESSION['JSVEHICLEMANAGER_SEARCH']);
        }
        $inquery = '';
        $clause = ' WHERE ';
        if ($title != null) {
            //$title = esc_sql($title);
            $inquery .= $clause . "title LIKE '%" . $title . "%'";
            $clause = ' AND ';
        }
        if (is_numeric($status))
            $inquery .= $clause . " status = " . $status;

        jsvehiclemanager::$_data['filter']['title'] = $title;
        jsvehiclemanager::$_data['filter']['status'] = $status;
        $db = new jsvehiclemanagerdb();
        //pagination
        $query = "SELECT COUNT(id) FROM `#__js_vehiclemanager_fueltypes` ";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jsvehiclemanager::$_data['total'] = $total;
        jsvehiclemanager::$_data[1] = JSVEHICLEMANAGERpagination::getPagination($total);

        //data
        $query = "SELECT * FROM `#__js_vehiclemanager_fueltypes` ";
        $query .= $inquery;
        $query .= " ORDER BY ordering ASC LIMIT " . JSVEHICLEMANAGERpagination::$_offset . ", " . JSVEHICLEMANAGERpagination::$_limit;
        $db->setQuery($query);
        jsvehiclemanager::$_data[0] = $db->loadObjectList();
        return;
    }

    function updateIsDefault($id) {
        if (is_numeric($id)) {
            $db = new jsvehiclemanagerdb();
            $query = "UPDATE `#__js_vehiclemanager_fueltypes` SET isdefault = 0 WHERE id != " . $id;
            $db->setQuery($query);
            $db->query();
        }
    }

    function validateFormData(&$data) {
        $canupdate = false;
        $db = new jsvehiclemanagerdb();
        if ($data['id'] == '') {
            $result = $this->isAlreadyExist($data['title']);
            if ($result == true) {
                return ALREADY_EXIST;
            } else {
                $query = "SELECT max(ordering) AS maxordering FROM `#__js_vehiclemanager_fueltypes`";
                $db->setQuery($query);
                $ordering = $db->loadResult();
                $data['ordering'] = $ordering + 1;
            }

            if ($data['status'] == 0) {
                $data['isdefault'] = 0;
            } else {
                if (isset($data['isdefault']) && $data['isdefault'] == 1) {
                    $canupdate = true;
                }
            }
        } else {
            if ($data['jsvehiclemanager_isdefault'] == 1) {
                $data['isdefault'] = 1;
                $data['status'] = 1;
            } else {
                if ($data['status'] == 0) {
                    $data['isdefault'] = 0;
                } else {
                    if (isset($data['isdefault']) && $data['isdefault'] == 1) {
                        $canupdate = true;
                    }
                }
            }
        }
        return $canupdate;
    }

    function storeFueltype($data) {
        if (empty($data))
            return false;

        $canupdate = $this->validateFormData($data);
        if ($canupdate === ALREADY_EXIST)
            return ALREADY_EXIST;

        $row = JSVEHICLEMANAGERincluder::getJSTable('fueltype');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        $data['alias'] = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($data['title']);
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }
        if ($canupdate) {
            $this->updateIsDefault($row->id);
        }

        return SAVED;
    }

    function deleteFueltype($ids) {
        if (empty($ids))
            return false;
        $row = JSVEHICLEMANAGERincluder::getJSTable('fueltype');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->fueltypeCanDelete($id) == true) {
                    if (!$row->delete($id)) {
                        $notdeleted += 1;
                    }
                } else {
                    $notdeleted += 1;
                }
            }else{
                $notdeleted += 1;
            }
        }
        if ($notdeleted == 0) {
            JSVEHICLEMANAGERmessages::$counter = false;
            return DELETED;
        } else {
            JSVEHICLEMANAGERmessages::$counter = $notdeleted;
            return DELETE_ERROR;
        }
    }

    function publishUnpublish($ids, $status) {
        if (empty($ids))
            return false;
        if (!is_numeric($status))
            return false;

        $row = JSVEHICLEMANAGERincluder::getJSTable('fueltype');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'status' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if ($this->fueltypeCanUnpublish($id)) {
                        if (!$row->update(array('id' => $id, 'status' => $status))) {
                            $total += 1;
                        }
                    } else {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        }
        if ($total == 0) {
            JSVEHICLEMANAGERmessages::$counter = false;
            if ($status == 1)
                return PUBLISHED;
            else
                return UN_PUBLISHED;
        }else {
            JSVEHICLEMANAGERmessages::$counter = $total;
            if ($status == 1)
                return PUBLISH_ERROR;
            else
                return UN_PUBLISH_ERROR;
        }
    }

    function fueltypeCanUnpublish($id) {
        if (!is_numeric($id))
            return false;
        $db = new jsvehiclemanagerdb();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_vehiclemanager_fueltypes` WHERE id = " . $id . " AND isdefault = 1)
                    AS total";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function fueltypeCanDelete($id) {
        if (!is_numeric($id))
            return false;
        $db = new jsvehiclemanagerdb();
        $query = " SELECT
                    ( SELECT COUNT(id) FROM `#__js_vehiclemanager_vehicles` WHERE adexpiryid = " . $id . ")
                    + ( SELECT COUNT(id) FROM `#__js_vehiclemanager_fueltypes` WHERE id = " . $id . " AND isdefault = 1)
                    AS total";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getFueltypeForCombo() {
        $db = new jsvehiclemanagerdb();
        $query = "SELECT id, title AS text FROM `#__js_vehiclemanager_fueltypes` WHERE status = 1 ORDER BY isdefault DESC,ordering ASC ";
        $db->setQuery($query);
        $fueltypes = $db->loadObjectList();
        return $fueltypes;
    }

    function isAlreadyExist($title) {
        if(!$title)
            return false;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_vehiclemanager_fueltypes` WHERE title = '" . $title . "'";
        $db->setQuery($query);
        $result = $db->loadResult();
        if ($result > 0)
            return true;
        else
            return false;
    }

    function getDefaultAgeId() {
        $db = new jsvehiclemanagerdb();
        $query = "SELECT id FROM `#__js_vehiclemanager_fueltypes` WHERE isdefault = 1";
        $db->setQuery($query);
        $id = $db->loadResult();
        return $id;
    }

    function getMessagekey(){
        $key = 'fueltypes';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }
}

?>
