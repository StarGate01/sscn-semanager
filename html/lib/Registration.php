<?php

    class Registration 
    {

        public $firstname;
        public $lastname;
        public $address;
        public $city;
        public $birth;
        public $birthDT;
        public $email;
        public $phone;
        public $newsletter;
        public $agb;
        public $signature;

        public function __construct() 
        {
            $this->clear();
        }

        public function clear()
        {
            $this->firstname = "";
            $this->lastname = "";
            $this->address = "";
            $this->city = "";
            $this->birth = "";
            $this->birthDT = NULL;
            $this->email = "";
            $this->phone = "";
            $this->newsletter = false;
            $this->agb = false;
            $this->signature = "";
        }

        public function parse_post($post)
        {
            if(isset($post["firstname"])) $this->firstname = htmlspecialchars($post["firstname"]);
            if(isset($post["lastname"])) $this->lastname = htmlspecialchars($post["lastname"]);
            if(isset($post["address"])) $this->address = htmlspecialchars($post["address"]);
            if(isset($post["city"])) $this->city = htmlspecialchars($post["city"]);
            if(isset($post["birth"])) $this->birth = htmlspecialchars($post["birth"]);
            if(isset($post["email"])) $this->email = htmlspecialchars($post["email"]);
            if(isset($post["phone"])) $this->phone = htmlspecialchars($post["phone"]);
            $this->newsletter = (isset($post["newsletter"]));
            $this->agb = (isset($post["agb"]));
            if(isset($post["signature"])) $this->signature = htmlspecialchars($post["signature"]);

            $this->birthDT = date_create_from_format('Y-m-d', $this->birth);
            if(!$this->birthDT) return "Ungültiges Geburtsdatum";

            return false;
        }

        public static function init_db($db)
        {
            $registrations = <<<EOD
            CREATE TABLE IF NOT EXISTS `registrations` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `creation` datetime NOT NULL DEFAULT current_timestamp(),
            `firstname` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `lastname` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `city` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `birth` date NOT NULL,
            `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `phone` text COLLATE utf8mb4_unicode_ci NOT NULL,
            `newsletter` tinyint(1) NOT NULL,
            `signature` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            EOD;
            return ($db->query($registrations) === true);
        }

        public function write_db($db)
        {
            $stmt = $conn->prepare("INSERT INTO registrations (`firstname`, `lastname`, `address`, `city`, `birth`, `email`, `phone`, `newsletter`, `signature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssis", $this->firstname, $this->lastname, $this->address, $this->city, $this->birthDT->format("Y-m-d"), $this->email, $this->phone, ($this->newsletter)? 1:0, $this->signature);
            return $stmt->execute();
        }

    }

?>