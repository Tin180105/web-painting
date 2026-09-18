<?php

function orderStatusMeta($status)
{
    $map = [
        "pending"   => ["Chờ xử lý", "status-pending"],
        "confirmed" => ["Đã xác nhận", "status-confirmed"],
        "shipping"  => ["Đang giao", "status-shipping"],
        "completed" => ["Hoàn thành", "status-completed"],
        "cancelled" => ["Đã hủy", "status-cancelled"],
    ];

    return $map[$status] ?? [$status, "status-pending"];
}
