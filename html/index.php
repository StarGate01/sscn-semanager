<?php

    include "./lib/DB.php";
    include "./lib/Message.php";
    include "./lib/Registration.php";

    include "./fragment/header.html";

    $db = DB::open();

    if($db === false)
    {
        Message::print("Es ist keine Verbindung zur Datenbank möglich!");
    }
    else
    {
        if(Registration::init_db($db))
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
                    Message::print("Der/die Teilnehmer/in konnte nicht registriert werden. Grund: ".$error.".");
                }
            }
        }
        else
        {
            Message::print("Datenbank-Tabelle für 'Registration' konnte nicht erstellt werden!");
        }
    }

    include "./fragment/registration.php";

    include "./fragment/footer.html";

?>