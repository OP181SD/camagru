<?php
function timeAgoFR($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) return $diff . ' secondes';
    elseif ($diff < 3600) return floor($diff / 60) . ' minutes';
    elseif ($diff < 86400) return floor($diff / 3600) . ' heures';
    else return floor($diff / 86400) . ' jours';
}
?>