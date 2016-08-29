<?php

namespace {
    require 'vendor/autoload.php';
}

namespace Nonces {

    function time($setTime = false)
    {
        static $time;
        if ($setTime) {
            $time = $setTime;
        }

        return $time;
    }

}