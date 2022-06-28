<?php

    class DB
    {

        public static function open()
        {
            $mysqli = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"], $_ENV["DB_DATABASE"], $_ENV["DB_PORT"]);
            if ($mysqli->connect_errno) return false;
            return $mysqli;
        }

        public static function close($db)
        {
            $db->close();
        }

    }

?>