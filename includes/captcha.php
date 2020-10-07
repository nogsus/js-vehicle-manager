<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSVEHICLEMANAGERcaptcha {

    function getCaptchaForForm() {
        $config_array = JSVEHICLEMANAGERincluder::getJSModel('configuration')->getConfigByFor('captcha');
        $rand = $this->randomNumber();
        $_SESSION['jsvehiclemanager_spamcheckid'] = $rand;
        $_SESSION['jsvehiclemanager_rot13'] = mt_rand(0, 1);
        $operator = 2;
        if ($operator == 2) {
            $tcalc = $config_array['owncaptcha_calculationtype'];
        }
        $max_value = 20;
        $negativ = 1;
        $operend_1 = mt_rand($negativ, $max_value);
        $operend_2 = mt_rand($negativ, $max_value);
        $operand = $config_array['owncaptcha_totaloperand'];
        if ($operand == 3) {
            $operend_3 = mt_rand($negativ, $max_value);
        }

        if ($config_array['owncaptcha_calculationtype'] == 2) { // Subtraction
            if ($config_array['owncaptcha_subtractionans'] == 1) {
                $ans = $operend_1 - $operend_2;
                if ($ans < 0) {
                    $one = $operend_2;
                    $operend_2 = $operend_1;
                    $operend_1 = $one;
                }
                if ($operand == 3) {
                    $ans = $operend_1 - $operend_2 - $operend_3;
                    if ($ans < 0) {
                        if ($operend_1 < $operend_2) {
                            $one = $operend_2;
                            $operend_2 = $operend_1;
                            $operend_1 = $one;
                        }
                        if ($operend_1 < $operend_3) {
                            $one = $operend_3;
                            $operend_3 = $operend_1;
                            $operend_1 = $one;
                        }
                    }
                }
            }
        }

        if ($tcalc == 0)
            $tcalc = mt_rand(1, 2);

        if ($tcalc == 1) { // Addition
            if ($_SESSION['jsvehiclemanager_rot13'] == 1) { // ROT13 coding
                if ($operand == 2) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = str_rot13(base64_encode($operend_1 + $operend_2));
                } elseif ($operand == 3) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = str_rot13(base64_encode($operend_1 + $operend_2 + $operend_3));
                }
            } else {
                if ($operand == 2) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = base64_encode($operend_1 + $operend_2);
                } elseif ($operand == 3) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = base64_encode($operend_1 + $operend_2 + $operend_3);
                }
            }
        } elseif ($tcalc == 2) { // Subtraction
            if ($_SESSION['jsvehiclemanager_rot13'] == 1) {
                if ($operand == 2) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = str_rot13(base64_encode($operend_1 - $operend_2));
                } elseif ($operand == 3) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = str_rot13(base64_encode($operend_1 - $operend_2 - $operend_3));
                }
            } else {
                if ($operand == 2) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = base64_encode($operend_1 - $operend_2);
                } elseif ($operand == 3) {
                    $_SESSION['jsvehiclemanager_spamcheckresult'] = base64_encode($operend_1 - $operend_2 - $operend_3);
                }
            }
        }
        $add_string = "";
        $add_string .= '<div><label for="' . $_SESSION['jsvehiclemanager_spamcheckid'] . '">';

        if ($tcalc == 1) {
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . __('Plus', 'js-vehicle-manager') . ' ' . $operend_2 . ' ' . __('Equals', 'js-vehicle-manager') . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . __('Plus', 'js-vehicle-manager') . ' ' . $operend_2 . ' ' . __('Plus', 'js-vehicle-manager') . ' ' . $operend_3 . ' ' . __('Equals', 'js-vehicle-manager') . ' ';
            }
        } elseif ($tcalc == 2) {
            $converttostring = 0;
            if ($operand == 2) {
                $add_string .= $operend_1 . ' ' . __('Minus', 'js-vehicle-manager') . ' ' . $operend_2 . ' ' . __('Equals', 'js-vehicle-manager') . ' ';
            } elseif ($operand == 3) {
                $add_string .= $operend_1 . ' ' . __('Minus', 'js-vehicle-manager') . ' ' . $operend_2 . ' ' . __('Minus', 'js-vehicle-manager') . ' ' . $operend_3 . ' ' . __('Equals', 'js-vehicle-manager') . ' ';
            }
        }

        $add_string .= '</label>';
        $add_string .= '<input type="text" name="' . $_SESSION['jsvehiclemanager_spamcheckid'] . '" id="' . $_SESSION['jsvehiclemanager_spamcheckid'] . '" size="3" class="inputbox ' . $rand . '" value="" data-validation="required" />';
        $add_string .= '</div>';

        return $add_string;
    }

    function randomNumber() {
        $pw = '';

        // first character has to be a letter
        $characters = range('a', 'z');
        $pw .= $characters[mt_rand(0, 25)];

        // other characters arbitrarily
        $numbers = range(0, 9);
        $characters = array_merge($characters, $numbers);

        $pw_length = mt_rand(4, 12);

        for ($i = 0; $i < $pw_length; $i++) {
            $pw .= $characters[mt_rand(0, 35)];
        }
        return $pw;
    }

    private function performChecks() {
        if(!isset($_SESSION['jsvehiclemanager_rot13']) && !isset($_SESSION['jsvehiclemanager_spamcheckresult'])){
            return false;
        }
        if ($_SESSION['jsvehiclemanager_rot13'] == 1) {
            $spamcheckresult = base64_decode(str_rot13($_SESSION['jsvehiclemanager_spamcheckresult']));
        } else {
            $spamcheckresult = base64_decode($_SESSION['jsvehiclemanager_spamcheckresult']);
        }
        $spamcheck = JSVEHICLEMANAGERrequest::getVar($_SESSION['jsvehiclemanager_spamcheckid'], '', 'post');
        unset($_SESSION['jsvehiclemanager_rot13']);
        unset($_SESSION['jsvehiclemanager_spamcheckid']);
        unset($_SESSION['jsvehiclemanager_spamcheckresult']);
        if (!is_numeric($spamcheckresult) || $spamcheckresult != $spamcheck) {
            return false; // Failed
        }
        return true;
    }

    function checkCaptchaUserForm() {
        if (!$this->performChecks())
            $return = 2;
        else
            $return = 1;
        return $return;
    }

}

?>