// ===== Small UI helpers for Oblig 2 =====

// 1) Confirm on elements with [data-confirm]
document.addEventListener('click', (e) => {
  const t = e.target.closest('[data-confirm]');
  if (t) {
    const msg = t.getAttribute('data-confirm') || 'Er du sikker?';
    if (!confirm(msg)) e.preventDefault();
  }
});

// 2) Auto-apply .btn to nav links to make them look nicer (optional)
document.querySelectorAll('nav a').forEach(a => {
  if (!a.classList.contains('btn')) a.classList.add('btn','btn-ghost','mt8');
});

// 3) Simple toast API
window.showToast = function (msg, ok = true) {
  let el = document.querySelector('.toast');
  if (!el) {
    el = document.createElement('div');
    el.className = 'toast';
    document.body.appendChild(el);
  }
  el.textContent = msg;
  el.style.background = ok ? '#0f5132' : '#842029';
  el.classList.add('show');
  setTimeout(() => el.classList.remove('show'), 2000);
};
