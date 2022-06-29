<?php
ob_start();

include "./lib/DB.php";
include "./lib/Message.php";
include "./lib/Registration.php";

include "./fragment/header.html";

$db = DB::open();

echo "<h2 class='mb-4'>Registrierungsdetails</h2>";

$rest_error = false;

if($db === false)
{
    header("HTTP/1.0 500 Internal Server Error");
    Message::print("Es ist keine Verbindung zur Datenbank möglich!");
}
else
{
    if(Registration::init_db($db))
    {
        $id = $_GET["id"];
        if(is_numeric($id))
        {
            $stmt = $db->prepare("SELECT * FROM `registrations` WHERE `id`=?;");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            if($result)
            {
                $row = $stmt->get_result();
                if($row)
                {
                    $rowdata = $row->fetch_assoc();
                    if($rowdata)
                    {
                        $reg = new Registration();
                        $reg->parse_db($rowdata);
                        
                        if($_SERVER["REQUEST_METHOD"] == "POST")
                        {
                            if(isset($_POST["delete"])) {
                                $result = $db->query("SELECT * FROM `registrations`;");
                                if($result)
                                {
                                    header('Location: list.php');
                                    ob_clean();
                                    ob_end_flush();
                                    exit();
                                }
                                else
                                {
                                    header("HTTP/1.0 500 Internal Server Error");
                                    ob_clean();
                                    ob_end_flush();
                                    exit();
                                }
                            }
                            else
                            {
                                $data = new Event();
                                $error = $data->parse_post($_POST);
                                if($error) 
                                {
                                    header("HTTP/1.0 400 Bad Request");
                                    ob_clean();
                                    ob_end_flush();
                                    exit();
                                }
                                if(!$data->write_db($db))
                                {
                                    header("HTTP/1.0 400 Bad Request");
                                    ob_clean();
                                    ob_end_flush();
                                    exit();
                                }
                            }
                        }
                        else
                        {
                            echo "<h4>".$reg->firstname." ".$reg->lastname."</h4>";
                            echo "<p>Registiert am ".$reg->creationDT->format("j.n.Y H:i")."<br />";
                            echo "<b>Adresse:</b> ".$reg->address." in ".$reg->city."<br />";
                            echo "<b>Geburtsdatum:</b> ".$reg->birthDT->format("j.n.Y")."<br />";
                            echo "<b>Kontakt:</b> <a href='mailto:".$reg->email."'>".$reg->email."</a>, Tel. <a href='tel:".$reg->phone."'>".$reg->phone."</a><br />";
                            echo "<b>Newsletter:</b> ".(($reg->newsletter)? "Ja":"Nein")."</p>";
                            echo "<h3>Logbuch</h3>";
                            
                            echo '<form onSubmit="return confirm(\'Diese Registrierung wirklich löschen?\') " action="user.php?id='.$id.'" method="post"><input type="hidden" name="delete" value="delete" /><button class="btn btn-danger" type="submit">Registrierung Löschen</button></form>';
                        }
                    }
                    else
                    {
                        header("HTTP/1.0 404 Not Found");
                        Message::print("Registrierung konnte nicht gefunden werden!");
                    }
                }
                else
                {
                    header("HTTP/1.0 404 Not Found");
                    Message::print("Registrierung konnte nicht gefunden werden!");
                }
            }
            else
            {
                header("HTTP/1.0 500 Internal Server Error");
                Message::print("Datenbank-Tabelle für 'registrations' konnte nicht gelesen werden!");
            }
        }
        else
        {
            header("HTTP/1.0 400 Bad Request");
            Message::print("Ungültige ID angegeben!");
        }
    }
    else
    {
        header("HTTP/1.0 500 Internal Server Error");
        Message::print("Datenbank-Tabelle für 'registrations' konnte nicht erstellt werden!");
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    ob_clean();
    ob_end_flush();
    exit();
}

include "./fragment/scripts.html";
include "./fragment/footer.html";

ob_end_flush();
?>