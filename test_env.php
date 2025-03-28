<?php
require 'config.php';

echo "SMTP_HOST: " . ($_ENV['SMTP_HOST'] ?? 'NOT LOADED') . "<br>";
echo "SMTP_USER: " . ($_ENV['SMTP_USER'] ?? 'NOT LOADED') . "<br>";
echo "SMTP_PASS: " . ($_ENV['SMTP_PASS'] ?? 'NOT LOADED') . "<br>";
