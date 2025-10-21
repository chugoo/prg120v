<?php
// oblig2/slettstudent.php — Slett student via bekreftelsesside (brukernavn)
require_once("dbtilkobling.php");

$method = $_SERVER['REQUEST_METHOD'];
$brukernavn = "";
$err = "";

// Hvis POST => forsøk å slette
if ($method === 'POST') {
  $brukernavn = isset($_POST['brukernavn']) ? trim($_POST['brukernavn']) : "";
  if ($brukernavn === "") {
    $err = "Mangler brukernavn.";
  } else {
    $del = mysqli_prepare($db, "DELETE FROM student WHERE brukernavn=?");
    mysqli_stmt_bind_param($del, "s", $brukernavn);
    if (!mysqli_stmt_execute($del)) {
      $err = "Kunne ikke slette (databasefeil).";
    } else {
      if (mysqli_stmt_affected_rows($del) > 0) {
        // vellykket
        header("Location: visallestudenter.php?ok=1");
        exit;
      } else {
        $err = "Fant ikke studenten å slette.";
      }
    }
    mysqli_stmt_close($del);
  }
} else {
  // GET => vis bekreftelsesside
  $brukernavn = isset($_GET['brukernavn']) ? trim($_GET['brukernavn']) : "";
}

// Hent student for visning
$student = null;
if ($brukernavn !== "") {
  $stmt = mysqli_prepare($db, "SELECT brukernavn, fornavn, etternavn, klassekode FROM student WHERE brukernavn=?");
  mysqli_stmt_bind_param($stmt, "s", $brukernavn);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $student = $res ? mysqli_fetch_assoc($res) : null;
  mysqli_stmt_close($stmt);
}

include("start.html");
?>
<article>
  <div class="row mb16">
    <h2 class="m0">Slett student</h2>
    <div class="right">
      <a class="btn" href="visallestudenter.php">Avbryt</a>
    </div>
  </div>

  <?php if ($err): ?>
    <div class="badge badge-danger mb16"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <?php if ($brukernavn === ""): ?>
    <div class="badge badge-warn">Mangler parameter: brukernavn.</div>
  <?php elseif (!$student): ?>
    <div class="badge badge-warn">Fant ingen student med brukernavn <strong><?= htmlspecialchars($brukernavn) ?></strong>.</div>
  <?php else: ?>
    <div style="border:1px solid var(--border); border-radius: var(--radius); padding:16px; background: var(--panel);">
      <p class="m0">Er du sikker på at du vil slette studenten nedenfor? Dette kan ikke angres.</p>
      <div class="mt16" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div><strong>Navn:</strong><br><?= htmlspecialchars($student['etternavn'].", ".$student['fornavn']) ?></div>
        <div><strong>Brukernavn:</strong><br>@<?= htmlspecialchars($student['brukernavn']) ?></div>
        <div><strong>Klasse:</strong><br><span class="badge"><?= htmlspecialchars($student['klassekode']) ?></span></div>
      </div>

      <form method="post" class="row mt16" action="slettstudent.php">
        <input type="hidden" name="brukernavn" value="<?= htmlspecialchars($student['brukernavn']) ?>">
        <button class="btn btn-danger" type="submit">Ja, slett</button>
        <a class="btn btn-ghost" href="visallestudenter.php">Nei, gå tilbake</a>
      </form>
    </div>
  <?php endif; ?>
</article>
<?php include("slutt.html"); ?>
