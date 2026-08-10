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

    /* ---- Scroll reveal ---- */
    var reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
        });
      }, { threshold: 0.14 });
      reveals.forEach(function (el) { io.observe(el); });
    } else {
      reveals.forEach(function (el) { el.classList.add('in'); });
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

    /* ---- Contact form toggle ---- */
    var openBtns = document.querySelectorAll('[data-open-form]');
    var form = document.getElementById('contactForm');
    openBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (form) {
          form.classList.add('show');
          form.scrollIntoView({ behavior: 'smooth', block: 'center' });
          var nm = form.querySelector('input[name="name"]');
          if (nm) setTimeout(function () { nm.focus(); }, 400);
        }
      });
    });

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
