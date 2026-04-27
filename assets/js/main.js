/**
 * SnapTube APK Website - Vanilla JavaScript
 * Lightweight interactions, no heavy libraries.
 */

(function() {
  'use strict';

  // ========== FAQ Accordion ==========
  const faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    question.addEventListener('click', () => {
      const isActive = item.classList.contains('active');
      
      // Close all others
      faqItems.forEach(other => {
        if (other !== item) other.classList.remove('active');
      });
      
      // Toggle current
      item.classList.toggle('active', !isActive);
    });
  });

  // ========== Scroll Reveal ==========
  const revealElements = document.querySelectorAll('.reveal');

  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -40px 0px'
  });

  revealElements.forEach(el => revealObserver.observe(el));

  // ========== Smooth Scroll for Anchor Links ==========
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        const headerOffset = 80;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }
    });
  });

  // ========== Header Shadow on Scroll ==========
  const header = document.querySelector('.header');
  let lastScroll = 0;

  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 10) {
      header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.08)';
    } else {
      header.style.boxShadow = 'none';
    }
    
    lastScroll = currentScroll;
  });

  // ========== Download Button Tracking ==========
  const downloadBtn = document.querySelector('.download-btn');
  if (downloadBtn) {
    downloadBtn.addEventListener('click', () => {
      console.log('Download initiated');
    });
  }

  // ========== Tags Input ==========
  const tagsContainer = document.getElementById('tagsContainer');
  const tagsInput = document.getElementById('seo_keywords_input');
  const tagsHidden = document.getElementById('seo_keywords');

  if (tagsContainer && tagsInput && tagsHidden) {
    let tags = [];

    function renderTags() {
      // Remove existing tag elements (keep the input)
      const existingTags = tagsContainer.querySelectorAll('.tag-item');
      existingTags.forEach(tag => tag.remove());

      // Insert tags before the input field
      tags.forEach((tagText, index) => {
        const tag = document.createElement('span');
        tag.className = 'tag-item';
        tag.textContent = tagText;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'tag-remove';
        removeBtn.innerHTML = '&times;';
        removeBtn.setAttribute('aria-label', 'Remove ' + tagText);
        removeBtn.addEventListener('click', () => {
          tags.splice(index, 1);
          updateTags();
        });

        tag.appendChild(removeBtn);
        tagsContainer.insertBefore(tag, tagsInput);
      });
    }

    function updateTags() {
      tagsHidden.value = tags.join(', ');
      renderTags();
    }

    function addTag(value) {
      const trimmed = value.trim();
      if (!trimmed) return;
      if (tags.includes(trimmed)) return; // Prevent duplicates
      tags.push(trimmed);
      updateTags();
    }

    // Initialize tags from the hidden input's existing value
    const initialValue = tagsHidden.value;
    if (initialValue) {
      tags = initialValue.split(',').map(t => t.trim()).filter(t => t);
      renderTags();
    }

    tagsInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        addTag(tagsInput.value);
        tagsInput.value = '';
      } else if (e.key === ',') {
        e.preventDefault();
        addTag(tagsInput.value);
        tagsInput.value = '';
      } else if (e.key === 'Backspace' && !tagsInput.value && tags.length > 0) {
        tags.pop();
        updateTags();
      }
    });

    tagsInput.addEventListener('input', (e) => {
      const val = tagsInput.value;
      if (val.includes(',')) {
        const parts = val.split(',');
        // Process all complete parts
        for (let i = 0; i < parts.length - 1; i++) {
          addTag(parts[i]);
        }
        // Keep the last part as current input (may be empty)
        tagsInput.value = parts[parts.length - 1];
      }
    });

    // Prevent accidental form submission on Enter
    tagsInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
      }
    });
  }

})();

