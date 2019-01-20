<?php
$pdo = new PDO('mysql:host=localhost:8889;dbname=mydb','root','root');
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
return $pdo;