<?php
// oblig2/flyttstudenter.php
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

// Hent alle studenter gruppert på klasse
$sqlS = "SELECT  fornavn, etternavn, klassekode FROM student ORDER BY etternavn, fornavn";
$resS = mysqli_query($db, $sqlS);
while ($s = mysqli_fetch_assoc($resS)) {
  if (!isset($klasser[$s['klassekode']])) {
    // Fallback om noen ligger på en slettet/ukjent klasse
    $klasser[$s['klassekode']] = ['kode'=>$s['klassekode'], 'navn'=>$s['klassekode'], 'studenter'=>[]];
  }
  $klasser[$s['klassekode']]['studenter'][] = $s;
}
?>

<style>
  .board { display:grid; gap:1rem; grid-template-columns: repeat(auto-fit, minmax(260px,1fr)); }
  .klasse {
    border:1px solid #ddd; border-radius:12px; padding:0.75rem; background:#fafafa;
  }
  .klasse h3 { margin:0 0 .5rem; font-size:1rem; }
  .dropzone {
    min-height: 200px; border:2px dashed #e0e0e0; border-radius:10px; padding:.5rem; background:#fff;
    transition: background .15s, border-color .15s;
  }
  .dropzone.over { background:#f0f8ff; border-color:#9dc0ff; }
  .student {
    user-select:none; background:#f7f7ff; border:1px solid #e6e6ff; border-radius:10px;
    padding:.5rem .6rem; margin:.35rem 0; cursor:grab;
  }
  .student:active { cursor:grabbing; }
  .toolbar { display:flex; gap:.5rem; align-items:center; margin:.25rem 0 .5rem; }
  .pill { font-size:.75rem; padding:.1rem .5rem; border-radius:999px; background:#eee; }
  .ghost { opacity: .4; }
  .toast {
    position: fixed; bottom: 16px; left: 50%; transform: translateX(-50%);
    background: #111; color: #fff; padding: 10px 14px; border-radius: 10px; display:none;
  }
</style>

<article>
  <h2>Flytt studenter mellom klasser (drag & drop)</h2>
  <p>Dra en student til en annen klasse for å oppdatere tilhørighet. Endringen lagres umiddelbart.</p>

  <div class="board" id="board">
    <?php foreach ($klasser as $k): ?>
      <section class="klasse" data-klassekode="<?= htmlspecialchars($k['kode']) ?>">
        <div class="toolbar">
          <h3 style="flex:1"><?= htmlspecialchars($k['kode']) ?> – <?= htmlspecialchars($k['navn']) ?></h3>
          <span class="pill"><span class="count"><?= count($k['studenter']) ?></span> stud.</span>
        </div>
        <div class="dropzone" data-klassekode="<?= htmlspecialchars($k['kode']) ?>">
          <?php foreach ($k['studenter'] as $s): ?>
            <div class="student" draggable="true"
                 data-studentnr="<?= htmlspecialchars($s['studentnr']) ?>">
              <?= htmlspecialchars($s['etternavn'] . ", " . $s['fornavn']) ?>
              <small style="opacity:.7">(#<?= htmlspecialchars($s['studentnr']) ?>)</small>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </div>

  <div class="toast" id="toast"></div>
</article>

<script>
// --- lille toast ---
function toast(msg, ok=true) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.background = ok ? '#0f5132' : '#842029';
  t.style.display = 'block';
  setTimeout(()=> (t.style.display='none'), 2000);
}

let dragEl = null;

document.querySelectorAll('.student').forEach(el => {
  el.addEventListener('dragstart', e => {
    dragEl = el;
    el.classList.add('ghost');
    e.dataTransfer.setData('text/plain', el.dataset.studentnr);
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
    const studentnr = e.dataTransfer.getData('text/plain');
    if (!studentnr || !dragEl) return;

    const toKlasse = zone.dataset.klassekode;
    const fromZone = dragEl.closest('.dropzone');
    const fromKlasse = fromZone?.dataset.klassekode;
    if (!toKlasse || !fromKlasse || toKlasse === fromKlasse) return;

    // Optimistisk UI: flytt i DOM umiddelbart
    zone.appendChild(dragEl);
    updateCounts();

    try {
      const resp = await fetch('move_student.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ studentnr, to_klassekode: toKlasse })
      });
      const data = await resp.json();
      if (!resp.ok || !data.success) {
        // Revert
        fromZone.appendChild(dragEl);
        updateCounts();
        toast(data.error || 'Kunne ikke flytte student.', false);
      } else {
        toast('Student flyttet.');
      }
    } catch (err) {
      fromZone.appendChild(dragEl);
      updateCounts();
      toast('Nettfeil – endringen ble ikke lagret.', false);
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

