<?php
ob_start();

include "./lib/DB.php";
include "./lib/Message.php";
include "./lib/Registration.php";
include "./lib/Event.php";

include "./fragment/header.html";

$db = DB::open();

if($db === false)
{
    header("HTTP/1.0 500 Internal Server Error");
    Message::print("Es ist keine Verbindung zur Datenbank möglich!");
}
else
{
    if(Registration::init_db($db) && Event::init_db($db))
    {
        $data = new Registration();
        
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $error = $data->parse_post($_POST);
            if($error === false && !$data->write_db($db)) $error = "Fehler beim Schreiben der Datenbank";
            
            if($error === false) 
            {
                $data->clear();
                Message::print("Der/die Teilnehmer/in wurde erfolgreich registriert.", "success", "result_message");
            }
            else 
            {
                header("HTTP/1.0 400 Bad Request");
                Message::print("Der/die Teilnehmer/in konnte nicht registriert werden. Grund: ".$error.".");
            }
        }
    }
    else
    {
        header("HTTP/1.0 500 Internal Server Error");
        Message::print("Datenbank-Tabellen konnten nicht erstellt werden!");
    }
}

include "./fragment/registration.php";

include "./fragment/scripts.html";
echo '<script src="js/index.js"></script>';
include "./fragment/footer.html";

ob_end_flush();
?>