<?php
// oblig2/move_student.php — oppdaterer klassekode basert på brukernavn
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success'=>false, 'error'=>'Kun POST er tillatt']);
  exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$brukernavn = isset($data['brukernavn']) ? trim($data['brukernavn']) : '';
$toKlasse = isset($data['to_klassekode']) ? trim($data['to_klassekode']) : '';

if ($brukernavn === '' || $toKlasse === '') {
  http_response_code(400);
  echo json_encode(['success'=>false, 'error'=>'Mangler brukernavn eller klassekode']);
  exit;
}

require_once("dbtilkobling.php");

// Sjekk at målklasse finnes
$stmt = mysqli_prepare($db, "SELECT 1 FROM klasse WHERE klassekode=?");
mysqli_stmt_bind_param($stmt, "s", $toKlasse);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) === 0) {
  http_response_code(400);
  echo json_encode(['success'=>false, 'error'=>'Målklasse finnes ikke']);
  exit;
}
mysqli_stmt_close($stmt);

// Oppdater studentens klassekode
$upd = mysqli_prepare($db, "UPDATE student SET klassekode=? WHERE brukernavn=?");
mysqli_stmt_bind_param($upd, "ss", $toKlasse, $brukernavn);
if (!mysqli_stmt_execute($upd)) {
  http_response_code(500);
  echo json_encode(['success'=>false, 'error'=>'Databasefeil ved oppdatering']);
  exit;
}
if (mysqli_stmt_affected_rows($upd) === 0) {
  http_response_code(404);
  echo json_encode(['success'=>false, 'error'=>'Fant ikke studenten']);
  exit;
}
mysqli_stmt_close($upd);

echo json_encode(['success'=>true]);
