<?php
require __DIR__ . '/../vendor/autoload.php';

echo "<pre>";
echo "Autoload OK\n";
echo "TicketController exists? ";
var_dump(class_exists(\App\Controllers\TicketController::class));
echo "</pre>";

