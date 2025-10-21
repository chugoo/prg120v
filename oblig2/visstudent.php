<?php
// oblig2/visstudent.php — Detaljside for én student (brukernavn)
require_once("dbtilkobling.php");

$brukernavn = isset($_GET['brukernavn']) ? trim($_GET['brukernavn']) : "";

$student = null;
if ($brukernavn !== "") {
  $sql = "SELECT s.brukernavn, s.fornavn, s.etternavn, s.klassekode, k.klassenavn
          FROM student s
          LEFT JOIN klasse k ON k.klassekode = s.klassekode
          WHERE s.brukernavn = ?";
  $stmt = mysqli_prepare($db, $sql);
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
    <h2 class="m0">Student</h2>
    <div class="right row">
      <a class="btn" href="visallestudenter.php">Til oversikten</a>
      <?php if ($student): ?>
        <a class="btn" href="flyttstudenter.php">Flytt (drag &amp; drop)</a>
        <a class="btn btn-danger" 
           href="slettstudent.php?brukernavn=<?= urlencode($student['brukernavn']) ?>"
           data-confirm="Slette <?= htmlspecialchars($student['brukernavn']) ?>?">
          Slett
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($brukernavn === ""): ?>
    <div class="badge badge-danger">Mangler parameter: brukernavn.</div>
  <?php elseif (!$student): ?>
    <div class="badge badge-warn">Fant ingen student med brukernavn <strong><?= htmlspecialchars($brukernavn) ?></strong>.</div>
  <?php else: ?>
    <div style="display:grid; gap:14px; grid-template-columns: 1fr;">
      <div style="border:1px solid var(--border); border-radius: var(--radius); padding:16px; background: var(--panel);">
        <div class="row">
          <h3 class="m0" style="flex:1"><?= htmlspecialchars($student['etternavn'].", ".$student['fornavn']) ?></h3>
          <span class="badge">@<?= htmlspecialchars($student['brukernavn']) ?></span>
        </div>
        <div class="mt8">
          <div><strong>Klasse:</strong> 
            <span class="badge"><?= htmlspecialchars($student['klassekode']) ?></span>
            <?php if (!empty($student['klassenavn'])): ?>
              <span style="opacity:.8;">— <?= htmlspecialchars($student['klassenavn']) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="row mt16">
          <a class="btn" href="visallestudenter.php?s=<?= urlencode($student['klassekode']) ?>">Se flere i klassen</a>
          <a class="btn" href="flyttstudenter.php">Flytt til annen klasse</a>
          <div class="right">
            <a class="btn btn-danger" 
               href="slettstudent.php?brukernavn=<?= urlencode($student['brukernavn']) ?>"
               data-confirm="Slette <?= htmlspecialchars($student['brukernavn']) ?>?">
              Slett student
            </a>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</article>
<?php include("slutt.html"); ?>
