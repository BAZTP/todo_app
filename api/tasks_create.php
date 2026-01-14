<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/_json.php";

$title = trim($_POST["title"] ?? "");
$priority = trim($_POST["priority"] ?? "medium");
$due_date = trim($_POST["due_date"] ?? "");

if($title === "") json_out(["ok"=>false, "error"=>"Título requerido"], 400);
if(!in_array($priority, ['high','medium','low'], true)) $priority = 'medium';
if($due_date === '') $due_date = null;

$stmt = $mysqli->prepare("INSERT INTO tasks(title, priority, due_date) VALUES(?,?,?)");
$stmt->bind_param("sss", $title, $priority, $due_date);
if(!$stmt->execute()) json_out(["ok"=>false, "error"=>$stmt->error], 500);

json_out(["ok"=>true, "id"=>$mysqli->insert_id]);
