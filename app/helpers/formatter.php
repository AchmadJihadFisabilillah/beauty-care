<?php
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function rupiah($amount): string
{
    return 'Rp' . number_format((float)$amount, 0, ',', '.');
}

function old(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}
