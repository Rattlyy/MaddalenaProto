<?php
if($msg == "ping") {
  $MadelineProto->messages->sendMessage(['peer' => $chatID, 'message' => "pong", 'parse_mode' => "HTML"], ['noResponse' => true]);
}
 ?>
