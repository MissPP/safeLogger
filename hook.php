<?php

Class hook
{
    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('location:slentry.php?module=login');
        }
    }
}

?>