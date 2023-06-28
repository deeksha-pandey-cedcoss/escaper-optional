<?php

namespace time;
// class time
class Time {

    public  function dat() {

        date_default_timezone_set('Asia/Kolkata');
        return date("Y-m-d h:i:sa");
    }
}