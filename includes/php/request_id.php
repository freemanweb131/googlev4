<?php
function generate_request_id() {
    $unique_id = uniqid('', true);

    $token = md5($unique_id);

    return $token;
}
?>