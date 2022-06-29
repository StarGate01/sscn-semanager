<?php

date_default_timezone_set('UTC');

class Registration 
{
    
    public $id;
    public $creationDT;
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
        $this->id = NULL;
        $this->creationDT = NULL;
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
        
        $this->birthDT = date_create_from_format('j.n.Y', $this->birth);
        if(!$this->birthDT) return "Ungültiges Geburtsdatum";

        if(trim($this->email) == "" && trim($this->phone) == "") return "E-Mail Adresse oder Telefonnummer ist erforderlich";
        
        return false;
    }
    
    public function parse_db($row)
    {
        $this->id = $row["id"];
        $this->creationDT = date_create_from_format('Y-m-d H:i:s', $row["creation"]);
        $this->firstname = $row["firstname"];
        $this->lastname = $row["lastname"];
        $this->address = $row["address"];
        $this->city = $row["city"];
        $this->birthDT = date_create_from_format('Y-m-d', $row["birth"]);
        $this->email = $row["email"];
        $this->phone = $row["phone"];
        $this->newsletter = ($row["newsletter"] == 1);
        $this->agb = true;
        $this->signature = $row["signature"];
    }
    
    public function write_db($db)
    {
        $stmt = $db->prepare("INSERT INTO registrations (`firstname`, `lastname`, `address`, `city`, `birth`, `email`, `phone`, `newsletter`, `signature`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);");
        $newsletter_int = ($this->newsletter)? 1:0;
        $stmt->bind_param("sssssssis", $this->firstname, $this->lastname, $this->address, $this->city, $this->birthDT->format("Y-m-d"), $this->email, $this->phone, $newsletter_int, $this->signature);
        return $stmt->execute();
    }
        
}
    
?>