<?php
ob_start();

include "./lib/DB.php";
include "./lib/Message.php";
include "./lib/Registration.php";

include "./fragment/header.html";

function print_button($state, $icon) {
    if($state) {
        echo '<span class="change_text text-success">Angemeldet</span><button class="btn change_button change_button_remove btn-danger"><i aria-hidden="true" class="mdi mdi-'.$icon.'"></i> | Abmelden</button>';
    } else {
        echo '<span class="change_text text-danger">Nicht Angemeldet</span><button class="btn change_button change_button_add btn-success"><i aria-hidden="true" class="mdi mdi-'.$icon.'"></i> | Anmelden</button>';
    }
}

$db = DB::open();

echo "<h2>Registrierungen verwalten</h2>";

if($db === false)
{
    header("HTTP/1.0 500 Internal Server Error");
    Message::print("Es ist keine Verbindung zur Datenbank möglich!");
}
else
{
    if(Registration::init_db($db))
    {
        $result = $db->query("SELECT * FROM `registrations`;");
        if($result)
        {
            include "./fragment/list_header.html";
            while($row = $result->fetch_assoc())
            {
                $reg = new Registration();
                $reg->parse_db($row);
                
                $surf_active = (rand(0,1) == 1);
                $sup_active = (rand(0,1) == 1);
                
                echo '<tr><td /><th style="white-space: nowrap;" scope="row"><span class="change_text">'.$reg->id.'</span><a class="btn change_button btn-primary" href="user.php?id='.$reg->id.'" role="button">'.$reg->id.' | <i aria-hidden="true" class="mdi mdi-pencil"></i></a></th>';
                echo "<td>".$reg->firstname."</td><td>".$reg->lastname."</td><td>".$reg->creationDT->format("j.n.Y H:i")."</td><td style='white-space: nowrap;'>";
                print_button($surf_active, "surfing");
                echo "</td><td style='white-space: nowrap;'>";
                print_button($sup_active, "bowl-mix");
                echo "</td></tr>";
            }
            echo "</table>";
        }
        else
        {
            header("HTTP/1.0 500 Internal Server Error");
            Message::print("Datenbank-Tabelle für 'registrations' konnte nicht gelesen werden!");
        }
    }
    else
    {
        header("HTTP/1.0 500 Internal Server Error");
        Message::print("Datenbank-Tabelle für 'registrations' konnte nicht erstellt werden!");
    }
}

include "./fragment/scripts.html";
echo '<script src="js/list.js"></script>';
include "./fragment/footer.html";

ob_end_flush();
?>