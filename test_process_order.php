<?php
file_put_contents("logs/debug_log.txt", date('c') . " | POST: " . json_encode($_POST) . "\n", FILE_APPEND);
echo "OK";
?>
