<?php
ob_start();
date_default_timezone_set('UTC');

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
    Message::print("Es ist keine Verbindung oder Initialisierung zur Datenbank möglich!");
}
else
{
    $id = $_GET["id"];
    if(is_numeric($id))
    {
        $stmt = $db->prepare(DB::QUERY_CORRELATE." WHERE r.`id`=?;");
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
                    $dt = $reg->creationDT;
                    $dt->setTimezone(new DateTimeZone('Europe/Berlin'));
                    echo "<p>Registiert am ".$dt->format("j.n.Y H:i")."<br />";
                    echo "<b>Adresse:</b> ".$reg->address." in ".$reg->city."<br />";
                    echo "<b>Geburtsdatum:</b> ".$reg->birthDT->format("j.n.Y")."<br />";
                    echo "<b>Kontakt:</b> <a href='mailto:".$reg->email."'>".$reg->email."</a>, Tel. <a href='tel:".$reg->phone."'>".$reg->phone."</a><br />";
                    echo "<b>Newsletter:</b> ".(($reg->newsletter)? "Ja":"Nein")."<br />";
                    echo "<b>Unterschrift:</b><br /><img class='signature_reproduce' src='".$reg->signature."' /></p>";
                  
                    echo "<h3>Anmeldungen</h3>";
                    $dt0 = null;
                    if($rowdata["e0_action_norm"] == 1)
                    {
                        $dt0 = date_create_from_format('Y-m-d H:i:s', $rowdata["e0_creation"]);
                        $dt0->setTimezone(new DateTimeZone('Europe/Berlin'));
                    }
                    $dt1 = null;
                    if($rowdata["e1_action_norm"] == 1)
                    {
                        $dt1 = date_create_from_format('Y-m-d H:i:s', $rowdata["e1_creation"]);
                        $dt1->setTimezone(new DateTimeZone('Europe/Berlin'));
                    }
                    echo "<p><i aria-hidden='true' class='mdi mdi-surfing'></i> <b>Surfkurs:</b> Aktuell <span class='text-".(($rowdata["e0_action_norm"] == 1)? "success'>angemeldet seit ".$dt0->format("H:i"):"danger'>unbeteiligt")."</span><br />";
                    echo "<i aria-hidden='true' class='mdi mdi-bowl-mix'></i> <b>SUP-Kurs:</b> Aktuell <span class='text-".(($rowdata["e1_action_norm"] == 1)? "success'>angemeldet seit ".$dt1->format("H:i"):"danger'>unbeteiligt")."</span></p>";

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
                                $dt = $ev->creationDT;
                                $dt->setTimezone(new DateTimeZone('Europe/Berlin'));
                                echo "<td>".$dt->format("j.n.Y H:i")." Uhr</td>";
                                echo "<td><i aria-hidden='true' class='mdi mdi-".(($ev->group == 0)? "surfing'></i> Surfkurs":"bowl-mix'></i> SUP-Kurs")."</td>";
                                echo "<td><span class='".(($ev->action == 1)? "text-success'>Angemeldet":"text-danger'>Abgemeldet")."</span></td>";
                            }
                            echo "</tbody></table>";
                        }
                        else
                        {
                            Message::print("Keine Anmeldungen gefunden!");
                        }
                    }
                    else
                    {
                        header("HTTP/1.0 500 Internal Server Error");
                        Message::print("Datenbank-Tabelle für 'registrations' konnte nicht gelesen werden!");
                    }
                    
                    echo "<h3>Verwaltung</h3>";
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

include "./fragment/scripts.html";
include "./fragment/footer.html";

ob_end_flush();
?>