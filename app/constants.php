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
    'completed' => 'Selesai',
    'picked' => 'Diambil',
]);

define('SERVICEORDER_REPAIR_STATUSES', [
    'incomplete' => 'Belum Selesai',
    'success' => 'Selesai (Sukses)',
    'failed' => 'Selesai (Gagal)',
]);

define('SERVICEORDER_PAYMENT_STATUSES', [
    'unpaid' => 'Belum Dibayar',
    'partially_paid' => 'Dibayar Sebagian',
    'fully_paid' => 'Lunas',
]);
