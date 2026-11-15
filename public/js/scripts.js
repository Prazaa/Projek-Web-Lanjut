/* ============================================================
   SIMKA (draf) — helper interaksi prototipe
   Modal: fokus terperangkap, Esc menutup, fokus kembali ke pemicu (design.md §5.10)
   Toast: role="status"/aria-live (design.md §5.11)
   Formulir: ringkasan galat di atas form + galat inline (panduan aksesibilitas)
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Menu mobile ---------- */
  function initMenus() {
    document.querySelectorAll('[data-menu]').forEach(function (btn) {
      var panel = document.getElementById(btn.getAttribute('aria-controls') || '');
      if (!panel) return;
      btn.addEventListener('click', function () {
        var open = panel.hasAttribute('hidden');
        if (open) { panel.removeAttribute('hidden'); } else { panel.setAttribute('hidden', ''); }
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    });
  }

  /* ---------- Modal ---------- */
  var lastTrigger = null;

  function openModal(id, trigger) {
    var modal = document.getElementById(id);
    if (!modal) return;
    lastTrigger = trigger || null;
    modal.classList.add('is-open');
    document.body.classList.add('is-locked');
    var target = modal.querySelector('[data-autofocus]') || modal.querySelector('a, button, input, select, textarea');
    if (target) target.focus();
  }

  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('is-open');
    document.body.classList.remove('is-locked');
    if (lastTrigger && document.contains(lastTrigger)) lastTrigger.focus();
    lastTrigger = null;
  }

  function getFocusable(modal) {
    return Array.prototype.filter.call(
      modal.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'),
      function (el) { return el.offsetParent !== null; }
    );
  }

  function initModals() {
    document.querySelectorAll('[data-open-modal]').forEach(function (btn) {
      btn.addEventListener('click', function () { openModal(btn.getAttribute('data-open-modal'), btn); });
    });
    document.querySelectorAll('.modal').forEach(function (modal) {
      modal.addEventListener('click', function (e) {
        // Klik overlay menutup, kecuali modal konfirmasi (data-locked)
        if (e.target === modal.querySelector('.modal__overlay') && !modal.hasAttribute('data-locked')) closeModal(modal);
      });
      var closers = modal.querySelectorAll('[data-close-modal]');
      Array.prototype.forEach.call(closers, function (btn) {
        btn.addEventListener('click', function () { closeModal(modal); });
      });
    });
    document.addEventListener('keydown', function (e) {
      var open = document.querySelector('.modal.is-open');
      if (!open) return;
      if (e.key === 'Escape') { closeModal(open); return; }
      if (e.key !== 'Tab') return;
      var items = getFocusable(open);
      if (!items.length) return;
      var first = items[0], last = items[items.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  }

  /* ---------- Toast ---------- */
  var toastTimer = null;
  function toast(region, message, type) {
    if (!region) return;
    var toastEl = region.querySelector('.toast');
    if (!toastEl) return;
    toastEl.className = 'toast ' + (type === 'error' ? 'toast-error' : 'toast-success') + (toastEl.classList.contains('is-open') ? ' is-open' : '');
    toastEl.querySelector('[data-toast-text]').textContent = message;
    toastEl.classList.add('is-open');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(function () { toastEl.classList.remove('is-open'); }, 3000);
  }

  function initToasts() {
    document.querySelectorAll('[data-toast]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        if (btn.tagName === 'A' && btn.getAttribute('href') === '#') e.preventDefault();
        var region = document.querySelector(btn.getAttribute('data-toast') || '.toast-region');
        toast(region, btn.getAttribute('data-toast-text') || 'Tersimpan.', btn.getAttribute('data-toast-type'));
      });
    });
  }

  /* ---------- Unggah berkas: tampilkan nama & status ---------- */
  function initUploads() {
    document.querySelectorAll('input[type="file"][data-upload]').forEach(function (input) {
      var label = input.closest('label') || input.parentElement;
      input.addEventListener('change', function () {
        var f = input.files && input.files[0];
        if (!f) return;
        label.classList.add('is-filled');
        var nameEl = label.querySelector('[data-file-name]');
        if (nameEl) nameEl.textContent = f.name;
        var metaEl = label.querySelector('[data-file-meta]');
        if (metaEl) {
          var kb = Math.max(1, Math.round(f.size / 1024));
          metaEl.textContent = (f.type && f.type.indexOf('pdf') > -1 ? 'PDF' : 'Gambar') + ' • ' + kb + ' KB';
        }
      });
    });
  }

  /* ---------- Ringkasan galat formulir ---------- */
  function showErrorSummary(form, invalids) {
    var box = form.querySelector('[data-error-summary]');
    if (!box) return;
    var list = box.querySelector('ul');
    list.innerHTML = '';
    invalids.forEach(function (item) {
      var li = document.createElement('li');
      var a = document.createElement('a');
      a.href = item.anchorId ? '#' + item.anchorId : '#';
      a.textContent = item.message;
      a.addEventListener('click', function () { item.input.focus(); });
      li.appendChild(a);
      list.appendChild(li);
      var wrap = item.input.closest('.field') || item.input.parentElement;
      var err = wrap.querySelector('.field-error');
      if (err) { err.textContent = item.message; err.style.display = 'block'; }
      item.input.classList.add('is-invalid');
    });
    box.classList.add('is-open');
    box.focus();
  }

  function initForms() {
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
      form.setAttribute('novalidate', '');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        form.querySelectorAll('[data-error-summary]').forEach(function (box) { box.classList.remove('is-open'); });
        form.querySelectorAll('.field-error').forEach(function (err) { err.style.display = 'none'; });
        form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });

        var invalids = [];
        form.querySelectorAll('[required]').forEach(function (input) {
          var ok = input.type === 'file' ? input.files.length > 0 : (input.type === 'radio' ? false : input.value.trim() !== '');
          if (input.type === 'radio') {
            var group = form.querySelectorAll('input[name="' + input.name + '"]');
            if (group.length && Array.prototype.some.call(group, function (r) { return r.checked; })) ok = true;
          }
          if (!ok) {
            var el = input.type === 'radio' ? form.querySelector('input[name="' + input.name + '"]') : input;
            var anchorId = (el && el.id) || (input.name ? 'field-' + input.name : '');
            invalids.push({
              input: el,
              anchorId: anchorId,
              message: input.getAttribute('data-message') || 'Bagian ini wajib diisi.'
            });
          }
        });

        if (invalids.length) {
          showErrorSummary(form, invalids);
        } else {
          var modalId = form.getAttribute('data-confirm-modal');
          var closeWrap = form.closest('.modal');
          if (modalId) { openModal(modalId, e.submitter || null); }
          else {
            toast(document.querySelector('.toast-region'), form.getAttribute('data-success-text') || 'Tersimpan.', 'success');
            if (form.hasAttribute('data-close-on-success') && closeWrap) closeModal(closeWrap);
          }
        }
      });
    });
  }

  /* ---------- Filter chip status (daftar pengajuan) ---------- */
  function initFilters() {
    document.querySelectorAll('[data-filter]').forEach(function (group) {
      var targets = document.querySelectorAll(group.getAttribute('data-target') || '[data-status]');
      var countSel = group.getAttribute('data-count');
      var totalSel = group.getAttribute('data-total');
      var emptySel = group.getAttribute('data-empty');
      var countEl = countSel ? document.querySelector(countSel) : null;
      var totalEl = totalSel ? document.querySelector(totalSel) : null;
      var emptyEl = emptySel ? document.querySelector(emptySel) : null;
      var chips = group.querySelectorAll('.filter-chip');
      if (totalEl) totalEl.textContent = targets.length;
      function apply(value) {
        var shown = 0;
        Array.prototype.forEach.call(targets, function (row) {
          var match = value === 'semua' || row.getAttribute('data-status') === value;
          row.hidden = !match;
          if (match) shown++;
        });
        chips.forEach(function (c) { c.setAttribute('aria-pressed', c.getAttribute('data-value') === value ? 'true' : 'false'); });
        if (countEl) countEl.textContent = shown;
        if (emptyEl) emptyEl.hidden = shown > 0;
      }
      chips.forEach(function (chip) {
        chip.addEventListener('click', function () { apply(chip.getAttribute('data-value')); });
      });
      apply('semua');
    });
  }

  /* ---------- Segmented tabs ---------- */
  function initSegs() {
    document.querySelectorAll('[data-seg]').forEach(function (group) {
      var tabs = group.querySelectorAll('[role="tab"]');
      tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
          tabs.forEach(function (t) {
            t.setAttribute('aria-selected', t === tab ? 'true' : 'false');
            var pane = document.getElementById(t.getAttribute('aria-controls'));
            if (pane) pane.hidden = t !== tab;
          });
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initMenus();
    initModals();
    initToasts();
    initUploads();
    initForms();
    initFilters();
    initSegs();
  });
})();
