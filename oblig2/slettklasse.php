<?php
// oblig2/slettklasse.php — Slett klasse med pen bekreftelse og FK-håndtering
require_once("dbtilkobling.php");

// Slå på exceptions for mysqli, så vi kan fange FK-feil pent
if (function_exists('mysqli_report')) {
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}

$method = $_SERVER['REQUEST_METHOD'];
$klassekode = "";
$err = "";

// POST: forsøk sletting
if ($method === 'POST') {
  $klassekode = isset($_POST['klassekode']) ? trim($_POST['klassekode']) : "";
  if ($klassekode === "") {
    $err = "Mangler klassekode.";
  } else {
    try {
      $stmt = mysqli_prepare($db, "DELETE FROM klasse WHERE klassekode=?");
      mysqli_stmt_bind_param($stmt, "s", $klassekode);
      mysqli_stmt_execute($stmt);
      if (mysqli_stmt_affected_rows($stmt) > 0) {
        // vellykket
        mysqli_stmt_close($stmt);
        header("Location: visalleklasser.php?ok=1");
        exit;
      } else {
        $err = "Fant ikke klassen å slette.";
      }
      mysqli_stmt_close($stmt);
    } catch (\mysqli_sql_exception $e) {
      // 1451 = Cannot delete or update a parent row: a foreign key constraint fails
      if ((int)$e->getCode() === 1451) {
        $err = "Kan ikke slette: Du må slette eller flytte studentene i klassen først.";
      } else {
        $err = "Databasefeil (".$e->getCode()."): " . $e->getMessage();
      }
    }
  }
} else {
  // GET: vis bekreftelse
  $klassekode = isset($_GET['klassekode']) ? trim($_GET['klassekode']) : "";
}

// Hent klassenavn + antall studenter for visning
$klasse = null;
if ($klassekode !== "") {
  // Hent info om klassen
  $stmt = mysqli_prepare($db, "
    SELECT k.klassekode, k.klassenavn, COALESCE(COUNT(s.brukernavn),0) AS antall
    FROM klasse k
    LEFT JOIN student s ON s.klassekode = k.klassekode
    WHERE k.klassekode = ?
  ");
  mysqli_stmt_bind_param($stmt, "s", $klassekode);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $klasse = $res ? mysqli_fetch_assoc($res) : null;
  mysqli_stmt_close($stmt);
}

include("start.html");
?>
<article>
  <div class="row mb16">
    <h2 class="m0">Slett klasse</h2>
    <div class="right">
      <a class="btn" href="visalleklasser.php">Avbryt</a>
    </div>
  </div>

  <?php if ($err): ?>
    <div class="badge badge-danger mb16"><?= htmlspecialchars($err) ?></div>
    <?php if ($klasse && (int)$klasse['antall'] > 0): ?>
      <div class="row mb16">
        <a class="btn" href="visallestudenter.php?s=<?= urlencode($klasse['klassekode']) ?>">Se studenter i klassen</a>
        <a class="btn" href="flyttstudenter.php">Flytt studenter</a>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <?php if ($klassekode === ""): ?>
    <div class="badge badge-warn">Mangler parameter: klassekode.</div>
  <?php elseif (!$klasse): ?>
    <div class="badge badge-warn">Fant ingen klasse med kode <strong><?= htmlspecialchars($klassekode) ?></strong>.</div>
  <?php else: ?>
    <div style="border:1px solid var(--border); border-radius: var(--radius); padding:16px; background: var(--panel);">
      <p class="m0">Er du sikker på at du vil slette denne klassen? Dette kan ikke angres.</p>

      <div class="mt16" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        <div><strong>Klassekode:</strong><br><?= htmlspecialchars($klasse['klassekode']) ?></div>
        <div><strong>Klassenavn:</strong><br><?= htmlspecialchars($klasse['klassenavn']) ?></div>
        <div><strong>Studenter i klassen:</strong><br>
          <?php if ((int)$klasse['antall'] > 0): ?>
            <span class="badge badge-warn"><?= (int)$klasse['antall'] ?></span>
            <small style="opacity:.8">— må flyttes/slettes først</small>
          <?php else: ?>
            <span class="badge badge-ok">0</span>
          <?php endif; ?>
        </div>
      </div>

      <form method="post" class="row mt16" action="slettklasse.php">
        <input type="hidden" name="klassekode" value="<?= htmlspecialchars($klasse['klassekode']) ?>">
        <button class="btn btn-danger" type="submit"
                <?php if ((int)$klasse['antall'] > 0): ?>disabled title="Det finnes studenter i klassen"<?php endif; ?>>
          Ja, slett klassen
        </button>
        <a class="btn btn-ghost" href="visalleklasser.php">Nei, gå tilbake</a>
        <?php if ((int)$klasse['antall'] > 0): ?>
          <a class="btn" href="flyttstudenter.php">Flytt studenter</a>
        <?php endif; ?>
      </form>
    </div>
  <?php endif; ?>
</article>
<?php include("slutt.html"); ?>
