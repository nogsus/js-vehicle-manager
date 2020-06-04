<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSVEHICLEMANAGERcountryModel {

    function storeCountry($data) {
        if (empty($data))
            return false;

        if ($data['id'] == '') {
            $result = $this->isCountryExist($data['name']);
            if ($result == true) {
                return ALREADY_EXIST;
            }
        }

        $data['shortCountry'] = str_replace(' ', '-', $data['name']);
        $row = JSVEHICLEMANAGERincluder::getJSTable('country');
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);
        if (!$row->bind($data)) {
            return SAVE_ERROR;
        }
        if (!$row->store()) {
            return SAVE_ERROR;
        }

        return SAVED;
    }

    function getCountrybyId($id) {
        if (!is_numeric($id))
            return false;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT * FROM `#__js_vehiclemanager_countries` WHERE id = " . $id;
        $db->setQuery($query);
        jsvehiclemanager::$_data[0] = $db->loadObject();
        return;
    }

    function getAllCountries() {

        $countryname = JSVEHICLEMANAGERrequest::getVar("countryname");
        $Status = JSVEHICLEMANAGERrequest::getVar("status");
        $states = JSVEHICLEMANAGERrequest::getVar("states");
        $city = JSVEHICLEMANAGERrequest::getVar("city");
        $formsearch = JSVEHICLEMANAGERrequest::getVar('JSVEHICLEMANAGER_form_search', 'post');
        if ($formsearch == 'JSVEHICLEMANAGER_SEARCH') {
            $_SESSION['JSVEHICLEMANAGER_SEARCH']['countryname'] = $countryname;
            $_SESSION['JSVEHICLEMANAGER_SEARCH']['status'] = $Status;
            $_SESSION['JSVEHICLEMANAGER_SEARCH']['states'] = $states;
            $_SESSION['JSVEHICLEMANAGER_SEARCH']['city'] = $city;
        }
        if (JSVEHICLEMANAGERrequest::getVar('pagenum', 'get', null) != null) {
            $countryname = (isset($_SESSION['JSVEHICLEMANAGER_SEARCH']['countryname']) && $_SESSION['JSVEHICLEMANAGER_SEARCH']['countryname'] != '') ? $_SESSION['JSVEHICLEMANAGER_SEARCH']['countryname'] : null;
            $Status = (isset($_SESSION['JSVEHICLEMANAGER_SEARCH']['status']) && $_SESSION['JSVEHICLEMANAGER_SEARCH']['status'] != '') ? $_SESSION['JSVEHICLEMANAGER_SEARCH']['status'] : null;
            $states = (isset($_SESSION['JSVEHICLEMANAGER_SEARCH']['states']) && $_SESSION['JSVEHICLEMANAGER_SEARCH']['states'] != '') ? $_SESSION['JSVEHICLEMANAGER_SEARCH']['states'] : null;
            $city = (isset($_SESSION['JSVEHICLEMANAGER_SEARCH']['city']) && $_SESSION['JSVEHICLEMANAGER_SEARCH']['city'] != '') ? $_SESSION['JSVEHICLEMANAGER_SEARCH']['city'] : null;
        } elseif ($formsearch !== 'JSVEHICLEMANAGER_SEARCH') {
            unset($_SESSION['JSVEHICLEMANAGER_SEARCH']);
        }
        $inquery = '';
        $clause = ' WHERE ';
        if ($countryname) {
            $inquery .= $clause . "  country.name LIKE '%" . $countryname . "%' ";
            $clause = " AND ";
        }
        if (is_numeric($Status)) {
            $inquery .= $clause . " country.enabled = " . $Status;
            $clause = " AND ";
        }

        if ($states == 1) {
            $inquery .= $clause . " (SELECT COUNT(id) FROM `#__js_vehiclemanager_states` AS state WHERE state.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        if ($city == 1) {
            $inquery .= $clause . " (SELECT COUNT(id) FROM `#__js_vehiclemanager_cities` AS city WHERE city.countryid = country.id) > 0 ";
            $clause = " AND ";
        }

        jsvehiclemanager::$_data['filter']['countryname'] = $countryname;
        jsvehiclemanager::$_data['filter']['status'] = $Status;
        jsvehiclemanager::$_data['filter']['states'] = $states;
        jsvehiclemanager::$_data['filter']['city'] = $city;
        $db = new jsvehiclemanagerdb();
        // Pagination
        $query = "SELECT COUNT(country.id) 
                    FROM `#__js_vehiclemanager_countries` AS country";
        $query .= $inquery;
        $db->setQuery($query);
        $total = $db->loadResult();
        jsvehiclemanager::$_data['total'] = $total;
        jsvehiclemanager::$_data[1] = JSVEHICLEMANAGERpagination::getPagination($total);

        // Data
        $query = "SELECT country.* FROM `#__js_vehiclemanager_countries` AS country";
        $query .= $inquery;
        $query .= " ORDER BY country.name ASC LIMIT " . JSVEHICLEMANAGERpagination::$_offset . ", " . JSVEHICLEMANAGERpagination::$_limit;
        $db->setQuery($query);
        jsvehiclemanager::$_data[0] = $db->loadObjectList();
        return;
    }

    function deleteCountries($ids) {
        if (empty($ids))
            return false;
        $row = JSVEHICLEMANAGERincluder::getJSTable('country');
        $notdeleted = 0;
        foreach ($ids as $id) {
            if(is_numeric($id)){
                if ($this->countryCanDelete($id) == true) {
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

        $row = JSVEHICLEMANAGERincluder::getJSTable('country');
        $total = 0;
        if ($status == 1) {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'enabled' => $status))) {
                        $total += 1;
                    }
                }else{
                    $total += 1;
                }
            }
        } else {
            foreach ($ids as $id) {
                if(is_numeric($id)){
                    if (!$row->update(array('id' => $id, 'enabled' => $status))) {
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

    function countryCanDelete($countryid) {
        if (!is_numeric($countryid))
            return false;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT
                    ( SELECT COUNT(veh.id) 
                        FROM `#__js_vehiclemanager_vehicles` AS veh
                        JOIN `#__js_vehiclemanager_cities` AS city ON city.id = veh.loccity
                        WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(veh.id) 
                        FROM `#__js_vehiclemanager_vehicles` AS veh
                        JOIN `#__js_vehiclemanager_cities` AS city ON city.id = veh.regcity
                        WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(user.id) 
                            FROM `#__js_vehiclemanager_users` AS user
                            JOIN `#__js_vehiclemanager_cities` AS city ON city.id = user.cityid
                            WHERE city.countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_vehiclemanager_states` WHERE countryid = " . $countryid . ")
                    + ( SELECT COUNT(id) FROM `#__js_vehiclemanager_cities` WHERE countryid = " . $countryid . ")
            AS total ";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return false;
        else
            return true;
    }

    function isCountryExist($country) {
        if (!$country)
            return;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT COUNT(id) FROM `#__js_vehiclemanager_countries` WHERE name = '" . $country . "'";
        $db->setQuery($query);
        $total = $db->loadResult();
        if ($total > 0)
            return true;
        else
            return false;
    }

    function getCountriesForCombo() {
        $db = new jsvehiclemanagerdb();
        $query = "SELECT id , name AS text FROM `#__js_vehiclemanager_countries` WHERE enabled = 1 ORDER BY name ASC ";
        $db->setQuery($query);
        $rows = $db->loadResult();
        return $rows;
    }

    function getCountryIdByName($name) { // new function coded
        if (!$name)
            return;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT id FROM `#__js_vehiclemanager_countries` WHERE REPLACE(LOWER(name), ' ', '') = REPLACE(LOWER('" . $name . "'), ' ', '') AND enabled = 1";
        $db->setQuery($query);
        $id = $db->loadResult();
        return $id;
    }

    function getMessagekey(){
        $key = 'country';if(is_admin()){$key = 'admin_'.$key;}return $key;
    }

}

?>