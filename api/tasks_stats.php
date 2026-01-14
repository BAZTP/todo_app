<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/_json.php";

$total = 0; $done = 0; $pending = 0; $overdue = 0; $due_today = 0;

$r = $mysqli->query("SELECT COUNT(*) c FROM tasks");
if($r) $total = (int)$r->fetch_row()[0];

$r = $mysqli->query("SELECT COUNT(*) c FROM tasks WHERE is_done=1");
if($r) $done = (int)$r->fetch_row()[0];

$r = $mysqli->query("SELECT COUNT(*) c FROM tasks WHERE is_done=0");
if($r) $pending = (int)$r->fetch_row()[0];

$r = $mysqli->query("SELECT COUNT(*) c FROM tasks WHERE is_done=0 AND due_date IS NOT NULL AND due_date < CURDATE()");
if($r) $overdue = (int)$r->fetch_row()[0];

$r = $mysqli->query("SELECT COUNT(*) c FROM tasks WHERE is_done=0 AND due_date = CURDATE()");
if($r) $due_today = (int)$r->fetch_row()[0];

json_out([
  "ok"=>true,
  "stats"=>[
    "total"=>$total,
    "pending"=>$pending,
    "done"=>$done,
    "overdue"=>$overdue,
    "due_today"=>$due_today
  ]
]);
