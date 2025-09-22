<?php
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-m9dYyzB97k928d8H38UeNDyM';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data dari form
$total = (int) $_POST['total_harga'];
$nama = $_POST['nama_driver'];
$email = $_POST['email_driver'];
$telepon = $_POST['telepon_driver'];
$id_mobil = $_POST['id_mobil'];

// Bisa diimprove dengan ambil detail mobil dari DB berdasarkan id_mobil
// Tapi untuk sekarang kita pakai dummy item
$item_details = array(
  array(
    'id' => $id_mobil,
    'price' => $total,
    'quantity' => 1,
    'name' => "Booking Mobil ID $id_mobil"
  )
);

$params = array(
  'transaction_details' => array(
    'order_id' => 'ORDER-' . time(),
    'gross_amount' => $total,
  ),
  'item_details' => $item_details,
  'customer_details' => array(
    'first_name' => $nama,
    'email' => $email,
    'phone' => $telepon,
  ),
);

try {
  $snapToken = \Midtrans\Snap::getSnapToken($params);
  echo json_encode(['token' => $snapToken]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
?>
