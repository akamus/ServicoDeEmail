<?php

namespace Helpers;

class Helper
{

    static function filtrarCampos($fields)
    {
        $vazio = 0;
        foreach ($fields as $key => $field) {

            if (!isset($_POST[$key])) {
                echo "campo: " . $key . " vazio!<br/>";
                $vazio++;
            }
        }

        if ($vazio > 0) {
            return false;
        } else {
            return true;
        }
    }

}
