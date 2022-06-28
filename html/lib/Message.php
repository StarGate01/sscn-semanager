<?php

    class Message
    {

        public static function print($message, $type = "danger", $id = "result_message_error")
        {
            echo '<div class="alert alert-'.$type.'" id="'.$id.'" role="alert">'.$message.'</div>';
        }

    }

?>