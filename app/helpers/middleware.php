<?php

function guest_only(): void
{
    if (is_logged_in()) {
        redirect(is_admin() ? '/admin/dashboard' : '/user/dashboard');
    }
}

function admin_only_view(): void
{
    require_admin();
}

function user_only_view(): void
{
    require_login();
}