/* Pikka Creatives — front-end interactions */
(function () {
  'use strict';

  /* ---- Preloader ---- */
  window.addEventListener('load', function () {
    var pre = document.getElementById('preloader');
    if (pre) setTimeout(function () { pre.classList.add('loaded'); }, 450);
  });

  document.addEventListener('DOMContentLoaded', function () {

    /* ---- Scroll reveal: sections slide/fade into view as you scroll to them.
       Two-column sections mark their own halves with .reveal-left / .reveal-right
       in the markup (image slides in from its side, text from its side); every
       other section is auto-wrapped with a plain fade-and-rise .reveal. ---- */
    document.querySelectorAll('section:not(.hero)').forEach(function (el) {
      if (!el.querySelector('.reveal-left, .reveal-right')) el.classList.add('reveal');
    });
    var revealTargets = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    if (revealTargets.length) {
      var revealReduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      if (revealReduceMotion || !('IntersectionObserver' in window)) {
        revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
      } else {
        var revealObserver = new IntersectionObserver(function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              revealObserver.unobserve(entry.target);
            }
          });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
        revealTargets.forEach(function (el) { revealObserver.observe(el); });
      }
    }

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

    /* ---- Our Work: category filter ---- */
    var workFilterBtns = document.querySelectorAll('[data-work-filter]');
    var workItems = document.querySelectorAll('[data-work-item]');
    var workEmpty = document.querySelector('[data-work-empty]');
    if (workFilterBtns.length && workItems.length) {
      workFilterBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
          var filter = btn.getAttribute('data-work-filter');
          workFilterBtns.forEach(function (b) { b.classList.toggle('active', b === btn); });
          var visibleCount = 0;
          workItems.forEach(function (item) {
            var match = filter === 'all' || item.getAttribute('data-category') === filter;
            item.classList.toggle('is-hidden', !match);
            if (match) visibleCount++;
          });
          if (workEmpty) workEmpty.style.display = visibleCount === 0 ? 'block' : 'none';
        });
      });
    }

    /* ---- Our Work: project detail pop-up ---- */
    var workModal = document.getElementById('workModal');
    if (workModal && workItems.length) {
      var wmCategory = document.getElementById('workModalCategory');
      var wmTitle = document.getElementById('workModalTitle');
      var wmDesc = document.getElementById('workModalDesc');
      var wmLink = document.getElementById('workModalLink');
      var wmLastFocused = null;

      var openWorkModal = function (card) {
        wmLastFocused = document.activeElement;
        var category = card.getAttribute('data-work-category') || '';
        var desc = card.getAttribute('data-work-desc') || '';
        var link = card.getAttribute('data-work-link') || '';
        wmTitle.textContent = card.getAttribute('data-work-title') || '';
        wmCategory.textContent = category;
        wmCategory.style.display = category ? '' : 'none';
        wmDesc.textContent = desc;
        wmDesc.style.display = desc ? '' : 'none';
        wmLink.style.display = link ? '' : 'none';
        if (link) wmLink.setAttribute('href', link);
        workModal.classList.add('open');
        workModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
      };
      var closeWorkModal = function () {
        workModal.classList.remove('open');
        workModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        if (wmLastFocused) wmLastFocused.focus();
      };

      workItems.forEach(function (card) {
        card.addEventListener('click', function () { openWorkModal(card); });
      });
      workModal.querySelectorAll('[data-close-work]').forEach(function (btn) {
        btn.addEventListener('click', closeWorkModal);
      });
      workModal.addEventListener('click', function (e) {
        if (e.target === workModal) closeWorkModal();
      });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && workModal.classList.contains('open')) closeWorkModal();
      });
    }

    /* ---- Hero slider ---- */
    var heroTextSlides = document.querySelectorAll('.hero-text-slide');
    var heroPhotoSlides = document.querySelectorAll('.hero-photo-slide');
    var heroDots = document.querySelectorAll('.hero-dot');
    if (heroTextSlides.length > 1) {
      var heroIndex = 0;
      var heroTimer = null;
      var heroReduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      var showHeroSlide = function (i) {
        heroIndex = (i + heroTextSlides.length) % heroTextSlides.length;
        heroTextSlides.forEach(function (el, idx) { el.classList.toggle('active', idx === heroIndex); });
        heroPhotoSlides.forEach(function (el, idx) { el.classList.toggle('active', idx === heroIndex); });
        heroDots.forEach(function (el, idx) { el.classList.toggle('active', idx === heroIndex); });
      };
      var stopHeroAutoplay = function () { if (heroTimer) clearInterval(heroTimer); };
      var startHeroAutoplay = function () {
        if (heroReduceMotion) return;
        stopHeroAutoplay();
        heroTimer = setInterval(function () { showHeroSlide(heroIndex + 1); }, 6000);
      };

      heroDots.forEach(function (dot) {
        dot.addEventListener('click', function () {
          showHeroSlide(parseInt(dot.getAttribute('data-hero-dot'), 10));
          startHeroAutoplay();
        });
      });

      var heroPrev = document.querySelector('[data-hero-prev]');
      var heroNext = document.querySelector('[data-hero-next]');
      if (heroPrev) heroPrev.addEventListener('click', function () { showHeroSlide(heroIndex - 1); startHeroAutoplay(); });
      if (heroNext) heroNext.addEventListener('click', function () { showHeroSlide(heroIndex + 1); startHeroAutoplay(); });

      var heroSection = document.querySelector('.hero');
      if (heroSection) {
        heroSection.addEventListener('mouseenter', stopHeroAutoplay);
        heroSection.addEventListener('mouseleave', startHeroAutoplay);
      }

      startHeroAutoplay();
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

    /* ---- Contact form submit (AJAX to send-message.php) ---- */
    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var msg = form.querySelector('.form-msg');
        var btn = form.querySelector('button[type="submit"]');
        var data = new FormData(form);
        btn.disabled = true; msg.textContent = 'Sending…'; msg.className = 'form-msg';
        fetch('send-message.php', { method: 'POST', body: data })
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
