// ===================================================================
//  SchoolERP — School Admin Dashboard  ·  template.js  v3.0
// ===================================================================
(function () {
  'use strict';

  const sidebar      = document.getElementById('sidebar');
  const mainEl       = document.getElementById('main');
  const overlay      = document.getElementById('overlay');
  const menuToggle   = document.getElementById('menuToggle');
  const bcCurrent    = document.getElementById('breadcrumbCurrent');

  let isMobile  = window.innerWidth < 768;
  let collapsed = false;
  window.addEventListener('resize', () => { isMobile = window.innerWidth < 768; });

  function closeAllPanels() {
    document.querySelectorAll('.open').forEach(el => {
      if (el !== overlay) el.classList.remove('open');
    });
  }

  function safeAddEvent(element, event, handler) {
    if (element) element.addEventListener(event, handler);
  }

  safeAddEvent(menuToggle, 'click', () => {
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

  safeAddEvent(overlay, 'click', () => {
    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('show');
    closeAllPanels();
  });

  const NAV_LABELS = {
    dashboard:     'Dashboard',
    incomes:       'Income',
    expenses:      'Expenses',
    payrolls:      'Payroll',
    reports:       'Reports',
    settings:      'Settings'
  };

  window.showPage = function (page) {
    const target = document.getElementById('page-' + page);
    if (!target) return;
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    target.classList.add('active');
    const navItem = document.querySelector('.nav-item[data-page="' + page + '"]');
    if (navItem) navItem.classList.add('active');
    if (bcCurrent) bcCurrent.textContent = NAV_LABELS[page] || page;
    if (isMobile) {
      sidebar.classList.remove('mobile-open');
      overlay.classList.remove('show');
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  document.querySelectorAll('.nav-item[data-page]').forEach(item => {
    item.addEventListener('click', () => showPage(item.dataset.page));
  });

  document.querySelectorAll('.topbar-btn[title="Notifications"]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const notifPanel = document.getElementById('notifPanel');
      if (notifPanel) notifPanel.classList.toggle('open');
    });
  });

  safeAddEvent(document, 'click', () => document.querySelectorAll('.more-menu.open').forEach(el => el.classList.remove('open')));

  window.showToast = function (msg, type) {
    const toastBox = document.getElementById('toastContainer');
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

  safeAddEvent(document.querySelectorAll('.alert-banner .close-btn')[0], 'click', event => {
    const btn = event.target;
    btn.closest('.alert-banner')?.remove();
  });

  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const group = btn.closest('.page-tabs');
      group?.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  console.log('SchoolERP template script loaded');
})();
