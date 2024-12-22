<?php
// TODO: PINDAH KE KELAS SUPAYA LEBIH MODERN

// User Roles Definitions
define('USER_ROLE_ADMIN', 'admin');
define('USER_ROLE_CASHIER', 'cashier');
define('USER_ROLE_TECHNICIAN', 'technician');

define('USER_ROLES', [
    USER_ROLE_ADMIN => 'Administrator',
    USER_ROLE_CASHIER => 'Kasir',
    USER_ROLE_TECHNICIAN => 'Teknisi',
]);

// Service Order Statuses Definitions
define('SERVICEORDER_ORDERSTATUS_OPEN', 'open');
define('SERVICEORDER_ORDERSTATUS_CLOSED', 'closed');
define('SERVICEORDER_ORDERSTATUS_CANCELED', 'canceled');

define('SERVICEORDER_ORDERSTATUSES', [
    SERVICEORDER_ORDERSTATUS_OPEN => 'Aktif',
    SERVICEORDER_ORDERSTATUS_CLOSED => 'Selesai',
    SERVICEORDER_ORDERSTATUS_CANCELED => 'Dibatalkan',
]);

// Service Order - Service Statuses Definitions
define('SERVICEORDER_SERVICESTATUS_RECEIVED', 'received');
define('SERVICEORDER_SERVICESTATUS_CHECKED', 'checked');
define('SERVICEORDER_SERVICESTATUS_WAITINGPARTS', 'waiting_parts');
define('SERVICEORDER_SERVICESTATUS_INPROGRESS', 'in_progress');
define('SERVICEORDER_SERVICESTATUS_COMPLETED', 'completed');
define('SERVICEORDER_SERVICESTATUS_PICKED', 'picked');

define('SERVICEORDER_SERVICESTATUSES', [
    SERVICEORDER_SERVICESTATUS_RECEIVED => 'Diterima',
    SERVICEORDER_SERVICESTATUS_CHECKED => 'Diperiksa',
    SERVICEORDER_SERVICESTATUS_WAITINGPARTS => 'Menunggu Sparepart',
    SERVICEORDER_SERVICESTATUS_INPROGRESS => 'Dalam Proses Pengerjaan',
    SERVICEORDER_SERVICESTATUS_COMPLETED => 'Selesai',
    SERVICEORDER_SERVICESTATUS_PICKED => 'Diambil',
]);

// Service Order - Repair Statuses Definitions
define('SERVICEORDER_REPAIRSTATUS_NOTFINISHED', 'not_finished');
define('SERVICEORDER_REPAIRSTATUS_SUCCESS', 'success');
define('SERVICEORDER_REPAIRSTATUS_FAILED', 'failed');
define('SERVICEORDER_REPAIRSTATUSES', [
    SERVICEORDER_REPAIRSTATUS_NOTFINISHED => 'Belum Selesai',
    SERVICEORDER_REPAIRSTATUS_SUCCESS => 'Selesai (Sukses)',
    SERVICEORDER_REPAIRSTATUS_FAILED => 'Selesai (Gagal)',
]);

// Service Order - Payment Statuses Definitions
define('SERVICEORDER_PAYMENTSTATUS_UNPAID', 'unpaid');
define('SERVICEORDER_PAYMENTSTATUS_PARTIALLYPAID', 'partially_paid');
define('SERVICEORDER_PAYMENTSTATUS_FULLYPAID', 'fully_paid');

define('SERVICEORDER_PAYMENTSTATUSES', [
    SERVICEORDER_PAYMENTSTATUS_UNPAID => 'Belum Dibayar',
    SERVICEORDER_PAYMENTSTATUS_PARTIALLYPAID => 'Dibayar Sebagian',
    SERVICEORDER_PAYMENTSTATUS_FULLYPAID => 'Lunas',
]);
