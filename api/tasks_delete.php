<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/_json.php";

$id = intval($_POST["id"] ?? 0);
if($id <= 0) json_out(["ok"=>false, "error"=>"ID inválido"], 400);

$stmt = $mysqli->prepare("DELETE FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);
if(!$stmt->execute()) json_out(["ok"=>false, "error"=>$stmt->error], 500);

json_out(["ok"=>true]);
