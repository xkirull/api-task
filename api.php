<?php

    function createUser(string $useragent, string $ip) {
        $token = bin2hex(random_bytes(16));
        
        DB::sql("INSERT INTO users (`token`, `useragent`, `ip`) VALUES (?,?,?)", [$token, $useragent, $ip]);
    
        setcookie("token", $token);

        return $token;
    }

    function userLogin() {
        setcookie("isAuth", "1");
    }

    function userLogout() {
        setcookie("isAuth", "0");
    }

    function captureEvent(string $eventName, string $userToken, bool $isAuth): void {
        $id = DB::getRow("SELECT id FROM users WHERE token = ? LIMIT 1", [$userToken])["id"];
        DB::sql("INSERT INTO events (`name`, `userId`, `isAuth`) VALUES (?,?,?)", [$eventName, $id, (int) $isAuth]);
    }

    function getEvent(string $startDate = "", string $endDate = "", string $eventName = "", string $options = "", string $selected = "") {
        $whereSQL = $eventName ? " WHERE `name` = \"$eventName\" " : "";

        if ($startDate) {
            if ($endDate == "") {
                $endDate = date('Y-m-d');
            }

            if ($whereSQL == "") {
                $whereSQL = " WHERE ";
            }

            $whereSQL .= "DATE_FORMAT(dt_created, '%Y-%m-%d') BETWEEN \"$startDate\" AND \"$endDate\" ";
        }

        $sql = "SELECT $selected, COUNT(*) as `countEvent` FROM events $whereSQL GROUP BY $options";

        $result = DB::getRows($sql, []);

        echo json_encode($result);
    }

    function getEventByEventName(string $startDate = "", string $endDate = "", string $eventName = "") {
        getEvent($startDate, $endDate, $eventName, "name", "name");
    }

    function getEventByUser(string $startDate = "", string $endDate = "", string $eventName = "") {
        getEvent($startDate, $endDate, $eventName, "name, userId", "userId, name");
    }

    function getEventByStatusUser(string $startDate = "", string $endDate = "", string $eventName = "") {
        getEvent($startDate, $endDate, $eventName , "name, isAuth", "isAuth, name");
    }
