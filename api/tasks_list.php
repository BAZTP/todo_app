<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/_json.php";

$q = trim($_GET['q'] ?? '');
$status = trim($_GET['status'] ?? 'all');     // all | pending | done
$priority = trim($_GET['priority'] ?? '');    // high | medium | low
$due = trim($_GET['due'] ?? '');              // today | overdue | nodue

$where = " WHERE 1=1 ";
$params = [];
$types = "";

if($q !== ''){
  $like = "%$q%";
  $where .= " AND title LIKE ? ";
  $params[] = $like;
  $types .= "s";
}

if($status === 'pending'){
  $where .= " AND is_done=0 ";
} elseif($status === 'done'){
  $where .= " AND is_done=1 ";
}

if(in_array($priority, ['high','medium','low'], true)){
  $where .= " AND priority=? ";
  $params[] = $priority;
  $types .= "s";
}

if($due === 'today'){
  $where .= " AND due_date = CURDATE() ";
} elseif($due === 'overdue'){
  $where .= " AND is_done=0 AND due_date IS NOT NULL AND due_date < CURDATE() ";
} elseif($due === 'nodue'){
  $where .= " AND due_date IS NULL ";
}

$sql = "SELECT id,title,priority,due_date,is_done,created_at FROM tasks $where ORDER BY is_done ASC, due_date IS NULL ASC, due_date ASC, id DESC";
$stmt = $mysqli->prepare($sql);
if($types) $stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
while($r = $res->fetch_assoc()) $rows[] = $r;

json_out(["ok"=>true, "tasks"=>$rows]);
