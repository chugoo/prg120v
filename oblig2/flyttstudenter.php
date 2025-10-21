<?php
// oblig2/flyttstudenter.php — bruker brukernavn istedenfor studentnr
include("start.html");
include("dbtilkobling.php");

// Hent alle klasser
$klasser = [];
$sql = "SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode";
$res = mysqli_query($db, $sql);
while ($row = mysqli_fetch_assoc($res)) {
  $klasser[$row['klassekode']] = [
    'kode' => $row['klassekode'],
    'navn' => $row['klassenavn'],
    'studenter' => []
  ];
}

// Hent alle studenter med brukernavn
$sqlS = "SELECT brukernavn, fornavn, etternavn, klassekode FROM student ORDER BY etternavn, fornavn";
$resS = mysqli_query($db, $sqlS);
while ($s = mysqli_fetch_assoc($resS)) {
  if (!isset($klasser[$s['klassekode']])) {
    $klasser[$s['klassekode']] = ['kode'=>$s['klassekode'], 'navn'=>$s['klassekode'], 'studenter'=>[]];
  }
  $klasser[$s['klassekode']]['studenter'][] = $s;
}
?>

<article>
  <h2>Flytt studenter mellom klasser (drag & drop)</h2>
  <p>Dra en student til en annen klasse for å oppdatere hvilken klasse brukeren tilhører.</p>

  <div class="board" id="board" style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fit,minmax(260px,1fr));">
    <?php foreach ($klasser as $k): ?>
      <section class="klasse" data-klassekode="<?= htmlspecialchars($k['kode']) ?>">
        <div class="row mb8">
          <h3 class="m0" style="flex:1"><?= htmlspecialchars($k['kode']) ?> – <?= htmlspecialchars($k['navn']) ?></h3>
          <span class="badge"><span class="count"><?= count($k['studenter']) ?></span> stud.</span>
        </div>
        <div class="dropzone" data-klassekode="<?= htmlspecialchars($k['kode']) ?>" style="min-height:200px;">
          <?php foreach ($k['studenter'] as $s): ?>
            <div class="student"
                 draggable="true"
                 data-brukernavn="<?= htmlspecialchars($s['brukernavn']) ?>">
              <strong><?= htmlspecialchars($s['etternavn']) ?>, <?= htmlspecialchars($s['fornavn']) ?></strong>
              <div style="font-size:0.85em; opacity:.7;">@<?= htmlspecialchars($s['brukernavn']) ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </div>

  <div class="toast" id="toast"></div>
</article>

<script>
// --- drag & drop for brukernavn ---
let dragEl = null;

document.querySelectorAll('.student').forEach(el => {
  el.addEventListener('dragstart', e => {
    dragEl = el;
    el.classList.add('ghost');
    e.dataTransfer.setData('text/plain', el.dataset.brukernavn);
    e.dataTransfer.effectAllowed = 'move';
  });
  el.addEventListener('dragend', () => {
    if (dragEl) dragEl.classList.remove('ghost');
    dragEl = null;
  });
});

document.querySelectorAll('.dropzone').forEach(zone => {
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('over'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('over'));

  zone.addEventListener('drop', async e => {
    e.preventDefault();
    zone.classList.remove('over');
    const brukernavn = e.dataTransfer.getData('text/plain');
    if (!brukernavn || !dragEl) return;

    const toKlasse = zone.dataset.klassekode;
    const fromZone = dragEl.closest('.dropzone');
    const fromKlasse = fromZone?.dataset.klassekode;
    if (!toKlasse || !fromKlasse || toKlasse === fromKlasse) return;

    zone.appendChild(dragEl);
    updateCounts();

    try {
      const resp = await fetch('move_student.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ brukernavn, to_klassekode: toKlasse })
      });
      const data = await resp.json();
      if (!resp.ok || !data.success) {
        fromZone.appendChild(dragEl);
        updateCounts();
        showToast(data.error || 'Kunne ikke flytte student.', false);
      } else {
        showToast('Student flyttet.');
      }
    } catch (err) {
      fromZone.appendChild(dragEl);
      updateCounts();
      showToast('Nettverksfeil – endringen ble ikke lagret.', false);
    }
  });
});

function updateCounts() {
  document.querySelectorAll('.klasse').forEach(k => {
    const count = k.querySelectorAll('.student').length;
    const c = k.querySelector('.count');
    if (c) c.textContent = count;
  });
}
</script>

<?php include("slutt.html"); ?>
