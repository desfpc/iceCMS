<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Setting Request Values Class
 *
 */

namespace ice\Web;

class RequestValues
{

    public $values;

    public function __construct(\stdClass $values)
    {
        $this->values = $values;
    }

    public function getRequestValues($valuesnames, $mode = 0)
    {
        if (is_array($valuesnames)) {
            foreach ($valuesnames as $valuename)
                $this->getRequestValue($valuename, $mode);
        } else {
            $this->getRequestValue($valuesnames, $mode);
        }
    }

    public function getRequestValue($valuename, $mode = 0)
    {
        if ($valuename != '') {
            if (isset($_REQUEST[$valuename])) {
                if (is_array($_REQUEST[$valuename])) {
                    $this->values->$valuename = array();
                    foreach ($_REQUEST[$valuename] as $val) {
                        if ($mode == 0) {
                            $this->values->$valuename[] = htmlspecialchars($val, ENT_QUOTES);
                        } else {
                            $this->values->$valuename[] = $val;
                        }
                    }
                } else {
                    if ($mode == 0) {
                        $this->values->$valuename = htmlspecialchars($_REQUEST[$valuename], ENT_QUOTES);
                    } else {
                        $this->values->$valuename = $_REQUEST[$valuename];
                    }
                }
            } else {
                $this->values->$valuename = '';
            }
        }
    }

    public function returnValues()
    {
        return ($this->values);
    }

}