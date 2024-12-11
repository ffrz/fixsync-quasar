<?php

define('USER_ROLES', [
    'admin' => 'Administrator',
    'cashier' => 'Kasir',
    'technician' => 'Teknisi',
]);

define('SERVICEORDER_ORDER_STATUSES', [
    'open' => 'Aktif',
    'closed' => 'Selesai',
    'canceled' => 'Dibatalkan',
]);

define('SERVICEORDER_SERVICE_STATUSES', [
    'received' => 'Diterima',
    'checked' => 'Diperiksa',
    'waiting_parts' => 'Menunggu Sparepart',
    'in_progress' => 'Dalam Proses Pengerjaan',
    'success' => 'Selesai (Sukses)',
    'failed' => 'Selesai (Gagal)',
    'picked' => 'Diambil',
]);

define('SERVICEORDER_PAYMENT_STATUSES', [
    'unpaid' => 'Belum Dibayar',
    'partially_paid' => 'Dibayar Sebagian',
    'fully_paid' => 'Lunas',
]);
