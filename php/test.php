<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

include 'Resellerclub.php';

$Resellerclub = new Resellerclub('437448', 'tHpAJCjErbY9EvXImA2tNqkX7DqYVHik', true);

$domain = isset($_GET['domain']) ? $_GET['domain'] : 'apple.com';
try {
    $res = $Resellerclub->checkDomainAvailability($domain);

} catch(Exception $e) {
    $res  = array(
        "status"     => false,
        "message"   => $e->getMessage()
    );
}
echo json_encode($res);