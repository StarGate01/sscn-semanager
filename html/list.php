<?php
ob_start();
date_default_timezone_set('UTC');

include "./lib/DB.php";
include "./lib/Message.php";
include "./lib/Registration.php";
include "./lib/Event.php";

include "./fragment/header.html";

function print_button($id, $group, $state, $icon, $time) {
    if($state) {
        $dt = date_create_from_format('Y-m-d H:i:s', $time);
        $dt->setTimezone(new DateTimeZone('Europe/Berlin'));
        echo '<span class="change_text text-success">Seit '.$dt->format("H:i").' Uhr</span>
        <form class="change_button" action="user.php?id='.$id.'" method="post">
            <input type="hidden" name="reg_id" value="'.$id.'" />
            <input type="hidden" name="group" value="'.$group.'" />
            <input type="hidden" name="action" value="0" />
            <button class="btn btn-danger" type="submit">
                <i aria-hidden="true" class="mdi mdi-'.$icon.'"></i> | Abmelden
            </button>
        </form>';
    } else {
        echo '<span class="change_text text-danger">Unbeteiligt</span>
        <form class="change_button" action="user.php?id='.$id.'" method="post">
            <input type="hidden" name="reg_id" value="'.$id.'" />
            <input type="hidden" name="group" value="'.$group.'" />
            <input type="hidden" name="action" value="1" />
            <button class="btn btn-success" type="submit">
                <i aria-hidden="true" class="mdi mdi-'.$icon.'"></i> | Anmelden
            </button>
        </form>';
    }
}

$db = DB::open();

echo "<h2>Registrierungen verwalten</h2>";

if($db === false)
{
    header("HTTP/1.0 500 Internal Server Error");
    Message::print("Es ist keine Verbindung oder Initialisierung zur Datenbank möglich!");
}
else
{
    $result = $db->query(DB::QUERY_CORRELATE.";");
    if($result)
    {
        include "./fragment/list_header.html";
        echo "<tbody>";
        while($row = $result->fetch_assoc())
        {
            $reg = new Registration();
            $reg->parse_db($row);

            echo '<tr><td /><th style="white-space: nowrap;" scope="row"><span class="change_text">'.$reg->id.'</span><a class="btn change_button btn-primary" href="user.php?id='.$reg->id.'" role="button">'.$reg->id.' | <i aria-hidden="true" class="mdi mdi-information"></i></a></th>';
            $dt = $reg->creationDT;
            $dt->setTimezone(new DateTimeZone('Europe/Berlin'));
            echo "<td>".$reg->firstname."</td><td>".$reg->lastname."</td><td>".$dt->format("j.n.Y H:i")."</td><td style='white-space: nowrap;'>";
            print_button($reg->id, 0, ($row["e0_action_norm"] == 1), "surfing", $row["e0_creation"]);
            echo "</td><td style='white-space: nowrap;'>";
            print_button($reg->id, 1, ($row["e1_action_norm"] == 1), "bowl-mix", $row["e1_creation"]);
            echo "</td></tr>";
        }
        echo "</tbody></table>";
    }
    else
    {
        header("HTTP/1.0 500 Internal Server Error");
        Message::print("Datenbank-Tabelle für 'registrations' konnte nicht gelesen werden!");
    }
}

include "./fragment/scripts.html";
echo '<script src="js/list.js"></script>';
include "./fragment/footer.html";

ob_end_flush();
?>