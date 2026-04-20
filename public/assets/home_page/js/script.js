// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {

  // ── Hamburger / Mobile Menu ─────────────────────────────────────
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const navMenuContainer = document.getElementById('navMenuContainer');

  if (hamburgerBtn && navMenuContainer) {
    hamburgerBtn.addEventListener('click', () => {
      hamburgerBtn.classList.toggle('is-open');
      navMenuContainer.classList.toggle('is-open');
      // Lock body scroll while menu is open
      document.body.style.overflow = navMenuContainer.classList.contains('is-open') ? 'hidden' : '';
    });

    // Handle dropdown toggles on mobile and close menu on normal link click
    navMenuContainer.querySelectorAll('.navbar__menu-item').forEach(item => {
      const link = item.querySelector('a');
      
      if (item.classList.contains('_has-dropdown')) {
        link.addEventListener('click', (e) => {
          if (window.innerWidth <= 768) {
            e.preventDefault(); // Prevent jump to top
            item.classList.toggle('is-active');
          }
        });
      } else {
        link.addEventListener('click', () => {
          hamburgerBtn.classList.remove('is-open');
          navMenuContainer.classList.remove('is-open');
          document.body.style.overflow = '';
        });
      }
    });

    // Also close menu when clicking on dropdown items
    navMenuContainer.querySelectorAll('.dropdown-item').forEach(link => {
      link.addEventListener('click', () => {
        hamburgerBtn.classList.remove('is-open');
        navMenuContainer.classList.remove('is-open');
        document.body.style.overflow = '';
      });
    });
  }

  // ── GSAP Setup ──────────────────────────────────────────────────
  gsap.registerPlugin(ScrollTrigger);

  // Hero Section Animations
  gsap.from('.hero__title', {
    opacity: 0,
    y: 30,
    duration: 1,
    ease: 'power2.out',
    delay: 0.4
  });

  gsap.from('.hero__badge', {
    opacity: 0,
    scale: 0.9,
    duration: 0.8,
    ease: 'back.out(1.7)',
    delay: 0.6
  });

  gsap.from('.hero__description', {
    opacity: 0,
    y: 20,
    duration: 1,
    ease: 'power2.out',
    delay: 0.8
  });

  gsap.from('.hero__ctas', {
    opacity: 0,
    y: 20,
    duration: 1,
    ease: 'power2.out',
    delay: 1
  });

  // Trust Bar Animation (Loads with Hero on Desktop)
  if (window.innerWidth > 768) {
    gsap.from('.trust-bar', {
      opacity: 0,
      y: 20,
      duration: 1,
      ease: 'power2.out',
      delay: 1.2
    });
  }

  // Section Fade-In Animations
  const sections = ['.offer', '.testimonials', '.contact', '.demo-cta', 'footer'];

  // Special handling for features section (content below trust bar)
  gsap.from('.features__container', {
    opacity: 0,
    y: 30,
    duration: 1.2,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '.features__container',
      start: 'top 85%',
      toggleActions: 'play none none none'
    }
  });

  // Mobile handling for trust bar (keep it as part of features scroll trigger if needed, or handle separately)
  if (window.innerWidth <= 768) {
    gsap.from('.trust-bar', {
      opacity: 0,
      y: 30,
      duration: 1.2,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: '.trust-bar',
        start: 'top 85%',
        toggleActions: 'play none none none'
      }
    });
  }

  sections.forEach(section => {
    gsap.from(section, {
      opacity: 0,
      y: 30,
      duration: 1.2,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: section,
        start: 'top 85%',
        toggleActions: 'play none none none'
      }
    });
  });

  // Comp Features Header fade-in (separate from pinned section)
  gsap.from('.comp-features__header', {
    opacity: 0,
    y: 30,
    duration: 1.2,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '.comp-features',
      start: 'top 90%',
      toggleActions: 'play none none none'
    }
  });

  // Staggered card animations
  const cardSections = [
    { trigger: '.offer__grid',          target: '.offer-card' },
    { trigger: '.features__container',  target: '.feature-item' },
    { trigger: '.comp-features__grid',  target: '.comp-feature-card' }
  ];

  cardSections.forEach(cs => {
    gsap.from(cs.target, {
      opacity: 0,
      y: 20,
      duration: 0.8,
      stagger: 0.2,
      ease: 'power2.out',
      scrollTrigger: {
        trigger: cs.trigger,
        start: 'top 80%',
        toggleActions: 'play none none none'
      }
    });
  });

  // ── Comprehensive Features Horizontal Scroll (desktop/tablet only) ──
  const isMobile = () => window.innerWidth <= 768;

  const grid = document.querySelector('.comp-features__grid');
  const container = document.querySelector('.comp-features');

  if (grid && container && !isMobile()) {
    gsap.to(grid, {
      x: () => {
        const horizontalDistance = grid.scrollWidth - window.innerWidth + 170; // 170 = left padding
        return -horizontalDistance;
      },
      ease: 'none',
      scrollTrigger: {
        trigger: '.comp-features',
        start: 'top top',
        end: () => `+=${grid.scrollWidth - window.innerWidth + 500}`,
        scrub: 1,
        pin: true,
        invalidateOnRefresh: true,
        anticipatePin: 1
      }
    });
  }

  // ── Swiper Initialization ────────────────────────────────────────
  new Swiper('.testimonial-swiper', {
    slidesPerView: 2,
    spaceBetween: 30,
    loop: true,
    navigation: {
      nextEl: '.testimonial-next',
      prevEl: '.testimonial-prev'
    },
    breakpoints: {
      320: {
        slidesPerView: 1,
        spaceBetween: 20
      },
      1024: {
        slidesPerView: 2,
        spaceBetween: 30
      }
    }
  });

});
