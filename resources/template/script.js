// ===================================================================
//  SchoolERP — School Admin Dashboard  ·  script.js  v3.0 (clean)
//  Single IIFE — no duplicate declarations
// ===================================================================
(function () {
  'use strict';

  // ── DOM REFS ──────────────────────────────────────────────
  const sidebar      = document.getElementById('sidebar');
  const mainEl       = document.getElementById('main');
  const overlay      = document.getElementById('overlay');
  const menuToggle   = document.getElementById('menuToggle');
  const bcCurrent    = document.getElementById('breadcrumbCurrent');
  const notifPanel   = document.getElementById('notifPanel');
  const profileDD    = document.getElementById('profileDropdown');
  const toastBox     = document.getElementById('toastContainer');

  // ── HELPERS ───────────────────────────────────────────────
  function closeAllPanels() {
    notifPanel && notifPanel.classList.remove('open');
    profileDD  && profileDD.classList.remove('open');
  }

  // ── TOAST ─────────────────────────────────────────────────
  window.showToast = function (msg, type) {
    if (!toastBox) return;
    const icons = {
      success: 'fa-circle-check',
      error:   'fa-circle-xmark',
      info:    'fa-circle-info',
      warning: 'fa-triangle-exclamation'
    };
    const t = document.createElement('div');
    t.className = 'toast toast-' + (type || 'success');
    t.innerHTML = `<i class="fa-solid ${icons[type] || icons.success}"></i><span>${msg}</span>`;
    toastBox.appendChild(t);
    requestAnimationFrame(() => t.classList.add('show'));
    setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 350); }, 3600);
  };

  // ── SIDEBAR TOGGLE ────────────────────────────────────────
  let isMobile  = window.innerWidth < 768;
  let collapsed = false;
  window.addEventListener('resize', () => { isMobile = window.innerWidth < 768; });

  menuToggle && menuToggle.addEventListener('click', () => {
    if (isMobile) {
      sidebar.classList.toggle('mobile-open');
      overlay.classList.toggle('show');
    } else {
      collapsed = !collapsed;
      sidebar.classList.toggle('collapsed', collapsed);
      mainEl.classList.toggle('expanded', collapsed);
    }
    closeAllPanels();
  });

  overlay && overlay.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('show');
    closeAllPanels();
  });

  // ── PAGE NAVIGATION ───────────────────────────────────────
  const NAV_LABELS = {
    dashboard:     'Dashboard',
    students:      'Students',
    invoicing:     'Invoicing',
    collections:   'Collections',
    expenses:      'Expenses',
    'other-income':'Other Income',
    accounting:    'Accounting',
    payroll:       'Payroll & HR',
    inventory:     'Stores & Inventory',
    transport:     'Transport',
    integrations:  'Integrations',
    reports:       'Reports & Analytics',
    roles:         'Roles & Permissions',
    settings:      'Settings'
  };

  function showPage(page) {
    // hide all pages
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    // deactivate nav items
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    // show target page
    const target = document.getElementById('page-' + page);
    if (target) target.classList.add('active');
    // activate nav item
    const navItem = document.querySelector('.nav-item[data-page="' + page + '"]');
    if (navItem) navItem.classList.add('active');
    // breadcrumb
    if (bcCurrent) bcCurrent.textContent = NAV_LABELS[page] || page;
    // close mobile sidebar
    if (isMobile) {
      sidebar.classList.remove('mobile-open');
      overlay.classList.remove('show');
    }
    closeAllPanels();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Attach click handlers to sidebar nav items
  document.querySelectorAll('.nav-item[data-page]').forEach(item => {
    item.addEventListener('click', () => showPage(item.dataset.page));
  });

  // expose for external use
  window.showPage = showPage;

  // ── NOTIFICATION PANEL ────────────────────────────────────
  document.querySelectorAll('.topbar-btn[title="Notifications"]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      notifPanel && notifPanel.classList.toggle('open');
      profileDD  && profileDD.classList.remove('open');
    });
  });

  window.markAllRead = function () {
    document.querySelectorAll('.notif-item.unread').forEach(i => i.classList.remove('unread'));
    document.querySelectorAll('.notif-dot').forEach(d => d.style.display = 'none');
    showToast('All notifications marked as read.', 'success');
  };

  // ── PROFILE DROPDOWN ──────────────────────────────────────
  const topbarAvatar = document.querySelector('.topbar-avatar');
  topbarAvatar && topbarAvatar.addEventListener('click', e => {
    e.stopPropagation();
    profileDD  && profileDD.classList.toggle('open');
    notifPanel && notifPanel.classList.remove('open');
  });

  // Close panels on outside click
  document.addEventListener('click', closeAllPanels);

  // ── MODAL SYSTEM ──────────────────────────────────────────
  window.openModal = function (id) {
    const m = document.getElementById(id);
    if (!m) return;
    // close any open modal first
    document.querySelectorAll('.modal-overlay.open').forEach(o => o.classList.remove('open'));
    m.classList.add('open');
    document.body.style.overflow = 'hidden';
  };

  window.closeModal = function (id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.classList.remove('open');
    document.body.style.overflow = '';
  };

  // data-modal attribute triggers
  document.addEventListener('click', function (e) {
    const trigger = e.target.closest('[data-modal]');
    if (trigger) {
      e.preventDefault();
      e.stopPropagation();
      window.openModal(trigger.dataset.modal);
      return;
    }
    // click on backdrop closes modal
    if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('open')) {
      e.target.classList.remove('open');
      document.body.style.overflow = '';
    }
  });

  // Escape key closes modal
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay.open').forEach(m => {
        m.classList.remove('open');
        document.body.style.overflow = '';
      });
    }
  });

  // ── PAGE-LEVEL TAB SWITCHER ───────────────────────────────
  // Generic: works for any set of .pt-btn / .st-btn + .tab-pane-content
  window.switchPageTab = function (btn, paneId) {
    // Deactivate sibling buttons
    const bar = btn.closest('.page-tabs, .settings-tabs');
    if (bar) bar.querySelectorAll('.pt-btn,.st-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    // Hide sibling panes (find them in the same .page section)
    const section = btn.closest('.page');
    if (section) {
      section.querySelectorAll('.tab-pane-content').forEach(p => p.classList.remove('active'));
      const pane = document.getElementById(paneId);
      if (pane) pane.classList.add('active');
    }
  };

  // Named wrappers so inline onclick="switchInvTab(this,'x')" still works
  window.switchInvTab  = window.switchPageTab;
  window.switchCollTab = window.switchPageTab;
  window.switchExpTab  = window.switchPageTab;

  // ── ADD COLLECTION MODAL ──────────────────────────────────
  let _selBalance = 0;
  let _selName    = '';

  window.filterStudentPicker = function (val) {
    const picker = document.getElementById('studentPicker');
    if (!picker) return;
    const v = val.toLowerCase().trim();
    picker.querySelectorAll('.sp-item').forEach(item => {
      item.style.display = item.textContent.toLowerCase().includes(v) ? '' : 'none';
    });
  };

  window.selectStudent = function (el, name, adm, balance) {
    document.querySelectorAll('.sp-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    const search = document.getElementById('collStudentSearch');
    if (search) { search.value = name + ' · ' + adm; search.classList.remove('error'); }
    _selName    = name;
    _selBalance = balance;
    window.updateCollPreview();
  };

  window.updateCollPreview = function () {
    const amtIn   = document.getElementById('collAmount');
    const preview = document.getElementById('collPreview');
    if (!preview) return;
    const amt = parseFloat(amtIn ? amtIn.value : 0) || 0;
    if (_selName && amt > 0) {
      preview.style.display = 'block';
      const ps = document.getElementById('prev-student');
      const pb = document.getElementById('prev-balance');
      const pa = document.getElementById('prev-amount');
      if (ps) ps.textContent = _selName;
      if (pb) pb.textContent = '₹ ' + _selBalance.toLocaleString();
      if (pa) pa.textContent = '₹ ' + amt.toLocaleString();
    } else {
      preview.style.display = 'none';
    }
  };

  window.submitCollection = function () {
    const amtIn  = document.getElementById('collAmount');
    const mthIn  = document.getElementById('collMethod');
    const dtIn   = document.getElementById('collDate');
    let valid = true;
    [amtIn, mthIn, dtIn].forEach(inp => {
      if (!inp) return;
      if (!inp.value) { inp.classList.add('error'); valid = false; }
      else inp.classList.remove('error');
    });
    if (!_selName) {
      const s = document.getElementById('collStudentSearch');
      if (s) s.classList.add('error');
      valid = false;
    }
    if (!valid) { showToast('Please fill all required fields.', 'error'); return; }
    showToast('Collection posted · Receipt #RCT-2025-0143 generated.', 'success');
    window.closeModal('modalAddCollection');
    // reset
    [amtIn, mthIn, dtIn].forEach(inp => { if (inp) { inp.value = ''; inp.classList.remove('error'); } });
    _selName = ''; _selBalance = 0;
    const prev = document.getElementById('collPreview');
    if (prev) prev.style.display = 'none';
    const sch = document.getElementById('collStudentSearch');
    if (sch) { sch.value = ''; sch.classList.remove('error'); }
    document.querySelectorAll('.sp-item').forEach(i => i.classList.remove('selected'));
  };

  // ── ALERT BANNER DISMISS ──────────────────────────────────
  document.querySelectorAll('.alert-banner .close-btn').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.alert-banner').remove());
  });

  // ── TABLE SORT ────────────────────────────────────────────
  document.querySelectorAll('thead th').forEach(th => {
    th.style.cursor = 'pointer';
    th.addEventListener('click', () => {
      const table = th.closest('table');
      if (!table) return;
      const idx  = th.cellIndex;
      const rows = Array.from(table.querySelectorAll('tbody tr'));
      const asc  = th.dataset.sortDir !== 'asc';
      th.dataset.sortDir = asc ? 'asc' : 'desc';
      rows.sort((a, b) => {
        const ta = (a.cells[idx] ? a.cells[idx].textContent : '').trim();
        const tb = (b.cells[idx] ? b.cells[idx].textContent : '').trim();
        const na = parseFloat(ta.replace(/[^0-9.-]/g, ''));
        const nb = parseFloat(tb.replace(/[^0-9.-]/g, ''));
        if (!isNaN(na) && !isNaN(nb)) return asc ? na - nb : nb - na;
        return asc ? ta.localeCompare(tb) : tb.localeCompare(ta);
      });
      rows.forEach(r => table.querySelector('tbody').appendChild(r));
    });
  });

  // ── LIVE SEARCH ───────────────────────────────────────────
  document.querySelectorAll('.search-box input').forEach(input => {
    input.addEventListener('input', function () {
      const card = this.closest('.card');
      if (!card) return;
      const val = this.value.toLowerCase();
      card.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
      });
    });
  });

  // ── STAT COUNTER ──────────────────────────────────────────
  function countUp(el, target, dur) {
    const s = performance.now();
    (function step(now) {
      const p = Math.min((now - s) / dur, 1);
      const e = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * e).toLocaleString();
      if (p < 1) requestAnimationFrame(step);
    })(performance.now());
  }
  setTimeout(() => {
    const vals = [258354, 44700, 83, 483597];
    document.querySelectorAll('#page-dashboard .stat-value').forEach((el, i) => {
      if (vals[i]) countUp(el, vals[i], 1400);
    });
  }, 300);

  // ── PROGRESS BARS ─────────────────────────────────────────
  setTimeout(() => {
    document.querySelectorAll('.progress-fill').forEach(bar => {
      const w = bar.style.width; bar.style.width = '0%';
      requestAnimationFrame(() => setTimeout(() => { bar.style.width = w; }, 80));
    });
  }, 200);

  // ── CHART BARS ────────────────────────────────────────────
  setTimeout(() => {
    document.querySelectorAll('.chart-bar').forEach((bar, i) => {
      const h = bar.style.height; bar.style.height = '0%';
      bar.style.transition = 'height .5s cubic-bezier(.4,0,.2,1)';
      setTimeout(() => { bar.style.height = h; }, 250 + i * 70);
    });
  }, 200);

  // ── M-PESA REF TOGGLE IN COLLECTION MODAL ─────────────────
  const collMethodSel = document.getElementById('collMethod');
  if (collMethodSel) {
    collMethodSel.addEventListener('change', function () {
      const grp = document.getElementById('mpesaRefGroup');
      if (grp) grp.style.display = this.value === 'M-Pesa' ? 'block' : 'none';
    });
  }

  console.log('SchoolERP v3 — navigation ready');

})(); // end IIFE

// ── TERM FILTER (tab-btn style) ──────────────────────────
window.setTermFilter = function (btn) {
  const parent = btn.closest('div');
  if (parent) parent.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
};

// ── GENERIC TAB SWITCHER (all pages use switchTab) ────────────────
// Works by finding the closest section.page ancestor and toggling
// .tab-pane elements within it
window.switchTab = function (btn, paneId) {
  // Find the parent page section
  const page = btn.closest('section.page') || btn.closest('.page');
  if (!page) return;

  // Deactivate all tab buttons in the nearest tab bar
  const tabBar = btn.closest('.page-tabs, .settings-tabs');
  if (tabBar) {
    tabBar.querySelectorAll('.pt-btn, .st-btn').forEach(b => b.classList.remove('active'));
  }
  btn.classList.add('active');

  // Hide all tab panes in this page section
  page.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

  // Show the target pane
  const target = document.getElementById(paneId);
  if (target) target.classList.add('active');
};

// ── TABLE FILTER BY SEARCH INPUT ─────────────────────────────────
window.filterTable = function (inputId, tableId) {
  const input = document.getElementById(inputId);
  const table = document.getElementById(tableId);
  if (!input || !table) return;
  const val = input.value.toLowerCase();
  table.querySelectorAll('tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
  });
};

// ── TABLE FILTER BY COLUMN VALUE (select/dropdown) ───────────────
window.filterTableByCol = function (select, tableId, colIndex) {
  const table = document.getElementById(tableId);
  if (!table) return;
  const val = select.value.toLowerCase().trim();
  table.querySelectorAll('tbody tr').forEach(row => {
    if (!val) { row.style.display = ''; return; }
    const cell = row.cells[colIndex];
    row.style.display = (cell && cell.textContent.toLowerCase().includes(val)) ? '' : 'none';
  });
};

// ── TOPBAR NOTIF TOGGLE (class-based button) ─────────────────────
document.querySelectorAll('.notif-toggle').forEach(btn => {
  btn.addEventListener('click', e => {
    e.stopPropagation();
    const notifPanel = document.getElementById('notifPanel');
    const profileDD  = document.getElementById('profileDropdown');
    notifPanel && notifPanel.classList.toggle('open');
    profileDD  && profileDD.classList.remove('open');
  });
});

// ── STUDENT GRADE FILTER ─────────────────────────────
window.setGradeFilter = function (btn, grade) {
  // Update active button
  document.querySelectorAll('.zeraki-grade-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  // Update heading
  const heading = document.getElementById('gradeHeading');
  if (heading) heading.textContent = grade === 'all' ? 'All Grades' : 'Grade ' + grade;

  // Filter rows
  const table = document.getElementById('stuClassTable');
  if (!table) return;
  let visible = 0;
  table.querySelectorAll('tbody tr').forEach(row => {
    const g = row.dataset.grade;
    const show = (grade === 'all' || g === grade);
    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });
};

// ── ACTIONS DROPDOWN (3-dot menu) ────────────────────
window.toggleStudentMenu = function (btn) {
  // Close any existing open menus
  document.querySelectorAll('.zeraki-action-menu').forEach(m => {
    if (m !== btn.nextSibling) m.remove();
  });

  if (btn.nextSibling && btn.nextSibling.classList && btn.nextSibling.classList.contains('zeraki-action-menu')) {
    btn.nextSibling.remove();
    return;
  }

  const menu = document.createElement('div');
  menu.className = 'zeraki-action-menu';
  menu.innerHTML = `
    <div class="zam-item" onclick="showToast('Viewing record…','info');this.closest('.zeraki-action-menu').remove()"><i class="fa-solid fa-eye"></i> View</div>
    <div class="zam-item" onclick="showToast('Edit form opened.','info');this.closest('.zeraki-action-menu').remove()"><i class="fa-solid fa-pen"></i> Edit</div>
    <div class="zam-item" onclick="showToast('Printed.','success');this.closest('.zeraki-action-menu').remove()"><i class="fa-solid fa-print"></i> Print</div>
    <div class="zam-item" style="color:var(--danger);" onclick="showToast('Record deleted.','error');this.closest('.zeraki-action-menu').remove()"><i class="fa-solid fa-trash"></i> Delete</div>
  `;
  btn.parentNode.style.position = 'relative';
  btn.parentNode.appendChild(menu);

  // Close on outside click
  setTimeout(() => {
    document.addEventListener('click', function closeMenu(e) {
      if (!menu.contains(e.target)) {
        menu.remove();
        document.removeEventListener('click', closeMenu);
      }
    });
  }, 10);
};

// ── BURSARY FORM TOGGLE ─────────────────────────────
window.showBursaryForm = function () {
  const list = document.getElementById('bursaryList');
  const form = document.getElementById('bursaryForm');
  if (list) list.style.display = 'none';
  if (form) form.style.display = 'block';
};
window.hideBursaryForm = function () {
  const list = document.getElementById('bursaryList');
  const form = document.getElementById('bursaryForm');
  if (list) list.style.display = 'block';
  if (form) form.style.display = 'none';
};
window.addBursaryRow = function () {
  const tbody = document.getElementById('bursaryBenefRows');
  if (!tbody) return;
  const rowCount = tbody.rows.length + 1;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${rowCount}.</td>
    <td><select class="form-control" style="font-size:12px;min-width:100px;"><option>ACTIVE</option><option>ALL</option></select></td>
    <td><select class="form-control" style="font-size:12px;min-width:180px;"><option value="">Select Student</option><option>Amina Kamau (SH/001)</option><option>Brian Mwangi (SH/002)</option><option>Felix Otieno (SH/006)</option></select></td>
    <td><input class="form-control" type="number" placeholder="Enter Amount" style="max-width:130px;" /></td>
    <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()"><i class="fa-solid fa-trash"></i></button></td>
  `;
  tbody.appendChild(tr);
};

// ── GRANT FORM TOGGLE ───────────────────────────────
window.showGrantForm = function () {
  const list = document.getElementById('grantList');
  const form = document.getElementById('grantForm');
  if (list) list.style.display = 'none';
  if (form) form.style.display = 'block';
};
window.hideGrantForm = function () {
  const list = document.getElementById('grantList');
  const form = document.getElementById('grantForm');
  if (list) list.style.display = 'block';
  if (form) form.style.display = 'none';
};
window.addGrantVoteRow = function () {
  const container = document.getElementById('grantVoteRows');
  if (!container) return;
  const div = document.createElement('div');
  div.className = 'grant-vote-row';
  div.style.cssText = 'border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:10px;';
  div.innerHTML = `
    <div class="form-row-2" style="margin-bottom:0;">
      <div class="form-group"><label class="form-label">Vote Head *</label><select class="form-control"><option value="">Select vote head</option><option>TUITION</option><option>BOM TEACHERS</option><option>LUNCH PROGRAMME</option><option>INFRASTRUCTURE</option><option>ICT LEVY</option></select></div>
      <div class="form-group"><label class="form-label">Total Amount *</label><input class="form-control" type="number" placeholder="Enter Amount" /></div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:8px;"><button class="btn btn-danger btn-sm" onclick="this.closest('.grant-vote-row').remove()"><i class="fa-solid fa-trash"></i></button></div>
  `;
  container.appendChild(div);
};

// ── PAYMENT VOUCHER FORM TOGGLE ─────────────────────
window.showPVForm = function () {
  const list = document.getElementById('pvList');
  const form = document.getElementById('pvForm');
  if (list) list.style.display = 'none';
  if (form) form.style.display = 'block';
};
window.hidePVForm = function () {
  const list = document.getElementById('pvList');
  const form = document.getElementById('pvForm');
  if (list) list.style.display = 'block';
  if (form) form.style.display = 'none';
};

// ── LOCAL ORDER FORM TOGGLE ─────────────────────────
window.showLOForm = function () {
  document.getElementById('loList') && (document.getElementById('loList').style.display = 'none');
  document.getElementById('loForm') && (document.getElementById('loForm').style.display = 'block');
};
window.hideLOForm = function () {
  document.getElementById('loList') && (document.getElementById('loList').style.display = 'block');
  document.getElementById('loForm') && (document.getElementById('loForm').style.display = 'none');
};
window.addOrderItemRow = function () {
  const c = document.getElementById('loOrderItems');
  if (!c) return;
  const d = document.createElement('div');
  d.className = 'order-item-row';
  d.style.cssText = 'border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:10px;';
  d.innerHTML = `
    <div class="form-row-2" style="margin-bottom:8px;">
      <div class="form-group"><label class="form-label">Vote Head</label><select class="form-control"><option value="">Select vote head</option><option>TUITION</option><option>OPERATIONS</option><option>INFRASTRUCTURE</option></select></div>
      <div class="form-group"><label class="form-label">Item Name</label><input class="form-control" placeholder="Enter Item Name" /></div>
    </div>
    <div class="form-row-2">
      <div class="form-group"><label class="form-label">Unit Cost</label><input class="form-control" type="number" placeholder="0.00" /></div>
      <div class="form-group"><label class="form-label">Quantity</label><input class="form-control" type="number" placeholder="0" /></div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:8px;"><button class="btn btn-danger btn-sm" onclick="this.closest('.order-item-row').remove()"><i class="fa-solid fa-trash"></i></button></div>
  `;
  c.appendChild(d);
};

// ── BILL FORM TOGGLE ────────────────────────────────
window.showBillForm = function () {
  document.getElementById('billList') && (document.getElementById('billList').style.display = 'none');
  document.getElementById('billForm') && (document.getElementById('billForm').style.display = 'block');
};
window.hideBillForm = function () {
  document.getElementById('billList') && (document.getElementById('billList').style.display = 'block');
  document.getElementById('billForm') && (document.getElementById('billForm').style.display = 'none');
};
window.addBillItemRow = function () {
  const c = document.getElementById('billInvoiceItems');
  if (!c) return;
  const d = document.createElement('div');
  d.className = 'order-item-row';
  d.style.cssText = 'border:1px solid var(--border);border-radius:var(--radius);padding:14px;margin-bottom:10px;';
  d.innerHTML = `
    <div class="form-row-2" style="margin-bottom:8px;">
      <div class="form-group"><label class="form-label">Vote Head *</label><select class="form-control"><option value="">Select vote head</option><option>TUITION</option><option>OPERATIONS</option><option>INFRASTRUCTURE</option></select></div>
      <div class="form-group"><label class="form-label">Item Name *</label><input class="form-control" placeholder="Enter Item Name" /></div>
    </div>
    <div class="form-row-2">
      <div class="form-group"><label class="form-label">Unit Cost *</label><input class="form-control" type="number" placeholder="0.00" /></div>
      <div class="form-group"><label class="form-label">Quantity *</label><input class="form-control" type="number" placeholder="0" /></div>
    </div>
    <div style="display:flex;justify-content:flex-end;margin-top:8px;"><button class="btn btn-danger btn-sm" onclick="this.closest('.order-item-row').remove()"><i class="fa-solid fa-trash"></i></button></div>
  `;
  c.appendChild(d);
};

// ── ADD GUARDIAN ROW ─────────────────────────────────
window.addGuardianRow = function () {
  const tbody = document.querySelector('#guardianTable tbody');
  if (!tbody) return;
  const rowNum = tbody.rows.length + 1;
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td style="color:var(--text-muted);font-weight:600;">${rowNum}.</td>
    <td><input class="form-control" placeholder="Enter guardian name" style="font-size:12px;" /></td>
    <td>
      <div style="display:flex;gap:4px;align-items:center;">
        <div style="display:flex;align-items:center;gap:4px;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:6px 8px;font-size:12px;white-space:nowrap;cursor:pointer;">
          <span>🇰🇪</span><span style="font-size:10px;color:var(--text-muted);">▾</span>
        </div>
        <input class="form-control" placeholder="Enter phone number" style="font-size:12px;flex:1;" />
      </div>
    </td>
    <td><input class="form-control" placeholder="Enter email" style="font-size:12px;" /></td>
    <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()" style="padding:4px 8px;"><i class="fa-solid fa-trash"></i></button></td>
  `;
  tbody.appendChild(tr);
};
