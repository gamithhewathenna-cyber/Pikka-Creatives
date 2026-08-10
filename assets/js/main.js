/* Pikka Creatives — front-end interactions */
(function () {
  'use strict';

  /* ---- Preloader ---- */
  window.addEventListener('load', function () {
    var pre = document.getElementById('preloader');
    if (pre) setTimeout(function () { pre.classList.add('loaded'); }, 450);
  });

  document.addEventListener('DOMContentLoaded', function () {

    /* ---- Header shrink on scroll ---- */
    var header = document.querySelector('.site-header');
    var onScroll = function () {
      if (window.scrollY > 30) header.classList.add('scrolled');
      else header.classList.remove('scrolled');
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    /* ---- Mobile nav ---- */
    var burger = document.querySelector('.burger');
    var links = document.querySelector('.nav-links');
    if (burger && links) {
      burger.addEventListener('click', function () { links.classList.toggle('open'); });
      links.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () { links.classList.remove('open'); });
      });
    }

    /* ---- Services accordion ---- */
    var items = document.querySelectorAll('.svc-item');
    items.forEach(function (item) {
      var head = item.querySelector('.svc-head');
      var body = item.querySelector('.svc-body');
      head.addEventListener('click', function () {
        var isOpen = item.classList.contains('open');
        items.forEach(function (o) {
          o.classList.remove('open');
          o.querySelector('.svc-body').style.maxHeight = null;
        });
        if (!isOpen) {
          item.classList.add('open');
          body.style.maxHeight = body.scrollHeight + 'px';
        }
      });
    });
    // open the first one by default
    if (items.length) {
      items[0].classList.add('open');
      var b0 = items[0].querySelector('.svc-body');
      b0.style.maxHeight = b0.scrollHeight + 'px';
    }

    /* ---- Contact form pop-up ---- */
    var modal = document.getElementById('contactModal');
    var form = document.getElementById('contactForm');
    var lastFocused = null;

    var openModal = function (e) {
      if (e) e.preventDefault();
      if (!modal) return;
      lastFocused = document.activeElement;
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-open');
      var nm = form && form.querySelector('input[name="name"]');
      if (nm) setTimeout(function () { nm.focus(); }, 300);
    };
    var closeModal = function () {
      if (!modal) return;
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-open');
      if (lastFocused) lastFocused.focus();
    };

    document.querySelectorAll('[data-open-form]').forEach(function (btn) {
      btn.addEventListener('click', openModal);
    });
    if (modal) {
      modal.querySelectorAll('[data-close-form]').forEach(function (btn) {
        btn.addEventListener('click', closeModal);
      });
      modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
      });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
      });
    }

    /* ---- Contact form submit (AJAX to contact.php) ---- */
    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var msg = form.querySelector('.form-msg');
        var btn = form.querySelector('button[type="submit"]');
        var data = new FormData(form);
        btn.disabled = true; msg.textContent = 'Sending…'; msg.className = 'form-msg';
        fetch('contact.php', { method: 'POST', body: data })
          .then(function (r) { return r.json(); })
          .then(function (res) {
            if (res.ok) {
              msg.textContent = res.message; msg.className = 'form-msg ok';
              form.reset();
            } else {
              msg.textContent = res.message || 'Something went wrong.'; msg.className = 'form-msg err';
            }
          })
          .catch(function () { msg.textContent = 'Network error. Please try again.'; msg.className = 'form-msg err'; })
          .finally(function () { btn.disabled = false; });
      });
    }

  });
})();
