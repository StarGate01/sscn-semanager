<?php
ob_start();

include "./lib/DB.php";
include "./lib/Message.php";
include "./lib/Registration.php";
include "./lib/Event.php";

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
    if(Registration::init_db($db) && Event::init_db($db))
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
                            $do_output = false;
                            if(isset($_POST["delete"])) {
                                $stmt = $db->prepare("DELETE FROM `registrations` WHERE `id`=?;");
                                $stmt->bind_param("i", $id);
                                $result = $stmt->execute();
                                if($result)
                                {
                                    header('Location: list.php');
                                }
                                else
                                {
                                    header("HTTP/1.0 500 Internal Server Error");
                                    Message::print("Registrierung konnte nicht gelöscht werden!");
                                    $do_output = true;
                                }
                            }
                            else
                            {
                                $data = new Event();
                                $error = $data->parse_post($_POST);
                                if(!$error)
                                {
                                    $data->write_db($db);
                                }
                                header('Location: list.php');
                            }
                            if(!$do_output) 
                            {
                                ob_clean();
                                ob_end_flush();
                                exit();
                            }
                        }
                        
                        echo "<h4>".$reg->firstname." ".$reg->lastname."</h4>";
                        echo "<p>Registiert am ".$reg->creationDT->format("j.n.Y H:i")."<br />";
                        echo "<b>Adresse:</b> ".$reg->address." in ".$reg->city."<br />";
                        echo "<b>Geburtsdatum:</b> ".$reg->birthDT->format("j.n.Y")."<br />";
                        echo "<b>Kontakt:</b> <a href='mailto:".$reg->email."'>".$reg->email."</a>, Tel. <a href='tel:".$reg->phone."'>".$reg->phone."</a><br />";
                        echo "<b>Newsletter:</b> ".(($reg->newsletter)? "Ja":"Nein")."</p>";
                       
                        echo "<h3>Logbuch</h3>";
                        $stmt = $db->prepare("SELECT * FROM `events` WHERE `reg_id`=? ORDER BY `creation` DESC;");
                        $stmt->bind_param("i", $id);
                        $result = $stmt->execute();
                        if($result)
                        {
                            $res = $stmt->get_result();
                            if($res && $res->num_rows > 0)
                            {
                                echo '<table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Zeitpunkt</th>
                                        <th scope="col">Angebot</th>
                                        <th scope="col">Aktion</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                while($row = $res->fetch_assoc())
                                {
                                    $ev = new Event();
                                    $ev->parse_db($row);
                                    
                                    echo '<tr><th style="white-space: nowrap;" scope="row">'.$ev->id.'</th>';
                                    echo "<td>".$ev->creationDT->format("j.n.Y H:i")."</td>";
                                    echo "<td>".(($ev->group == 0)? "Surfkurs":"SUP-Kurs")."</td>";
                                    echo "<td><span class='".(($ev->action == 0)? "text-success'>Angemeldet":"text-danger'>Abgemeldet")."</span></td>";
                                }
                                echo "</tbody></table>";
                            }
                            else
                            {
                                Message::print("Keine Aktionen gefunden!");
                            }
                        }
                        else
                        {
                            header("HTTP/1.0 500 Internal Server Error");
                            Message::print("Datenbank-Tabelle für 'registrations' konnte nicht gelesen werden!");
                        }
                        
                        echo "<h3>Löschen</h3>";
                        echo '<form onSubmit="return confirm(\'Diese Registrierung wirklich löschen?\')" action="user.php?id='.$id.'" method="post"><input type="hidden" name="delete" value="delete" /><button class="btn btn-danger" type="submit">Registrierung Löschen</button></form>';
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
        Message::print("Datenbank-Tabellen konnten nicht erstellt werden!");
    }
}

include "./fragment/scripts.html";
include "./fragment/footer.html";

ob_end_flush();
?>