<?php
require __DIR__ . '/auth.php';

$_SESSION = [];
session_destroy();

redirecionar('index.php');
