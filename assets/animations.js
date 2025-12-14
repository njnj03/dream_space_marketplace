/**
 * DreamSpace Realty - Smooth Animations & Interactions
 * Enhances UI with scroll-based animations and smooth transitions
 */

(function() {
  'use strict';

  // Hide loading screen after a brief delay
  const loadingScreen = document.getElementById('loading-screen');
  if (loadingScreen) {
    setTimeout(() => {
      loadingScreen.classList.add('loaded');
      setTimeout(() => {
        loadingScreen.remove();
      }, 500);
    }, 800);
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    setupScrollAnimations();
    setupSmoothScrollLinks();
    setupImageLazyLoad();
    setupNavigationEffects();
    setupCardInteractions();
    setupProfileDropdown();
  }

  /**
   * Scroll-based reveal animations
   */
  function setupScrollAnimations() {
    const observerOptions = {
      threshold: 0.15,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          // Add stagger delay for consecutive elements
          setTimeout(() => {
            entry.target.classList.add('revealed');
          }, index * 100);
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe all cards and grid items
    const elementsToAnimate = document.querySelectorAll('.card, .grid > *, .property');
    elementsToAnimate.forEach((el, index) => {
      // Add scroll-reveal class if not already animated
      if (!el.style.animation) {
        el.classList.add('scroll-reveal');
        observer.observe(el);
      }
    });
  }

  /**
   * Smooth scrolling for anchor links
   */
  function setupSmoothScrollLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href === '#' || href === '#!') return;
        
        const target = document.querySelector(href);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  }

  /**
   * Image lazy loading with fade-in effect
   */
  function setupImageLazyLoad() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    images.forEach(img => {
      if (img.complete) {
        img.classList.add('loaded');
      } else {
        img.addEventListener('load', function() {
          this.classList.add('loaded');
        });
      }
    });

    // Also handle images without lazy attribute
    document.querySelectorAll('img:not([loading])').forEach(img => {
      if (img.complete) {
        img.classList.add('loaded');
      } else {
        img.addEventListener('load', function() {
          setTimeout(() => {
            this.classList.add('loaded');
          }, 50);
        });
      }
    });
  }

  /**
   * Enhanced navigation effects
   */
  function setupNavigationEffects() {
    const nav = document.querySelector('nav.site-nav');
    if (!nav) return;

    let lastScroll = 0;
    let ticking = false;

    window.addEventListener('scroll', () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          const currentScroll = window.pageYOffset;
          
          // Add shadow on scroll
          if (currentScroll > 10) {
            nav.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.08)';
          } else {
            nav.style.boxShadow = 'none';
          }
          
          lastScroll = currentScroll;
          ticking = false;
        });
        ticking = true;
      }
    });

    // Highlight active section in navigation
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.menu a[href^="#"]');

    if (sections.length > 0 && navLinks.length > 0) {
      window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.clientHeight;
          if (window.pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
          }
        });

        navLinks.forEach(link => {
          link.classList.remove('active');
          if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active');
          }
        });
      });
    }
  }

  /**
   * Card hover interactions
   */
  function setupCardInteractions() {
    const cards = document.querySelectorAll('.card, .product-card');
    
    cards.forEach(card => {
      // Add ripple effect on click
      card.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
          position: absolute;
          width: ${size}px;
          height: ${size}px;
          left: ${x}px;
          top: ${y}px;
          background: rgba(14, 165, 233, 0.1);
          border-radius: 50%;
          transform: scale(0);
          animation: ripple-animation 0.6s ease-out;
          pointer-events: none;
        `;
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
      });

      // Parallax effect on mouse move
      card.addEventListener('mousemove', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        
        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;
        
        this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-6px) scale(1.02)`;
      });

      card.addEventListener('mouseleave', function() {
        this.style.transform = '';
      });
    });
  }

  // Add ripple animation to stylesheet
  if (!document.querySelector('#ripple-animation-style')) {
    const style = document.createElement('style');
    style.id = 'ripple-animation-style';
    style.textContent = `
      @keyframes ripple-animation {
        to {
          transform: scale(2);
          opacity: 0;
        }
      }
    `;
    document.head.appendChild(style);
  }

  /**
   * Profile dropdown functionality
   */
  function setupProfileDropdown() {
    const profileBtn = document.querySelector('.profile-btn');
    const dropdownMenu = document.querySelector('.dropdown-menu');
    
    if (!profileBtn || !dropdownMenu) return;

    // Toggle dropdown
    profileBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      
      if (isExpanded) {
        closeDropdown();
      } else {
        openDropdown();
      }
    });

    function openDropdown() {
      profileBtn.setAttribute('aria-expanded', 'true');
      dropdownMenu.classList.add('show');
    }

    function closeDropdown() {
      profileBtn.setAttribute('aria-expanded', 'false');
      dropdownMenu.classList.remove('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!profileBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
        closeDropdown();
      }
    });

    // Close dropdown on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDropdown();
      }
    });

    // Add keyboard navigation
    const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
    dropdownItems.forEach((item, index) => {
      item.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          const next = dropdownItems[index + 1];
          if (next) next.focus();
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          const prev = dropdownItems[index - 1];
          if (prev) prev.focus();
          else profileBtn.focus();
        }
      });
    });
  }

  /**
   * Button press effect
   */
  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('mousedown', function() {
      this.style.transform = 'translateY(0) scale(0.98)';
    });

    btn.addEventListener('mouseup', function() {
      this.style.transform = '';
    });
  });

  /**
   * Page transition effect
   */
  document.body.style.opacity = '0';
  document.body.style.transition = 'opacity 0.3s ease';
  
  requestAnimationFrame(() => {
    document.body.style.opacity = '1';
  });

  /**
   * Form input animations
   */
  document.querySelectorAll('input, textarea, select').forEach(input => {
    input.addEventListener('focus', function() {
      this.style.transform = 'scale(1.01)';
      this.style.transition = 'transform 0.2s ease';
    });

    input.addEventListener('blur', function() {
      this.style.transform = 'scale(1)';
    });
  });

  console.log('✨ DreamSpace Realty animations loaded');
})();
