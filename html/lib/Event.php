<?php

class Event 
{
    
    public $id;
    public $reg_id;
    public $creationDT;
    public $group;
    public $action;
    
    public function __construct() 
    {
        $this->clear();
    }
    
    public function clear()
    {
        $this->id = NULL;
        $this->reg_id = NULL;
        $this->creationDT = NULL;
        $this->group = NULL;
        $this->action = NULL;
    }
    
    public function parse_post($post)
    {
        if(isset($post["reg_id"])) $this->reg_id = htmlspecialchars($post["reg_id"]);
        if(isset($post["group"])) $this->group = htmlspecialchars($post["group"]);
        if(isset($post["action"])) $this->action = htmlspecialchars($post["action"]);
        return false;
    }
    
    public function parse_db($row)
    {
        $this->id = $row["id"];
        $this->reg_id = $row["reg_id"];
        $this->creationDT = date_create_from_format('Y-m-d H:i:s', $row["creation"]);
        $this->group = $row["group"];
        $this->action = $row["action"];
    }

    public function write_db($db)
    {
        $stmt = $db->prepare("INSERT INTO events (`reg_id`, `group`, `action`) VALUES (?, ?, ?);");
        $stmt->bind_param("iii", $this->reg_id, $this->group, $this->action);
        return $stmt->execute();
    }
    
}

?>