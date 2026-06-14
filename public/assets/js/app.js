document.querySelectorAll('[data-confirm]').forEach(function (el) {
  el.addEventListener('click', function (e) {
    if (!confirm(el.getAttribute('data-confirm'))) {
      e.preventDefault();
    }
  });
});

// FAQ Accordion & Search Logic
document.addEventListener('DOMContentLoaded', function () {
  const faqItems = document.querySelectorAll('.faq-item');
  const searchInput = document.getElementById('faqSearchInput');
  const searchClear = document.getElementById('faqSearchClear');
  const noResults = document.getElementById('faqNoResults');

  // 1. Accordion Toggle Logic
  faqItems.forEach(function (item) {
    const question = item.querySelector('.faq-question');
    const answer = item.querySelector('.faq-answer');

    question.addEventListener('click', function () {
      const isActive = item.classList.contains('active');

      // Close all other open items first for a clean accordion effect
      faqItems.forEach(function (otherItem) {
        if (otherItem !== item && otherItem.classList.contains('active')) {
          otherItem.classList.remove('active');
          otherItem.querySelector('.faq-answer').style.maxHeight = null;
        }
      });

      // Toggle current item
      if (isActive) {
        item.classList.remove('active');
        answer.style.maxHeight = null;
      } else {
        item.classList.add('active');
        answer.style.maxHeight = answer.scrollHeight + 'px';
      }
    });
  });

  // 2. Client-side Real-time Search Logic
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const query = this.value.toLowerCase().trim();
      let hasVisibleItems = false;

      // Show/Hide Clear Button
      if (query.length > 0) {
        searchClear.style.display = 'block';
      } else {
        searchClear.style.display = 'none';
      }

      faqItems.forEach(function (item) {
        const questionText = item.querySelector('.faq-question').textContent.toLowerCase();
        const answerText = item.querySelector('.faq-answer').textContent.toLowerCase();

        if (questionText.includes(query) || answerText.includes(query)) {
          item.style.display = '';
          hasVisibleItems = true;
          
          // Keep active ones expanded or let them adjust
          if (item.classList.contains('active')) {
            const answer = item.querySelector('.faq-answer');
            answer.style.maxHeight = answer.scrollHeight + 'px';
          }
        } else {
          item.style.display = 'none';
        }
      });

      // Show no results box if all items are hidden
      if (hasVisibleItems) {
        noResults.style.display = 'none';
      } else {
        noResults.style.display = 'block';
      }
    });

    // Clear Search Input when clicking 'X'
    searchClear.addEventListener('click', function () {
      searchInput.value = '';
      searchClear.style.display = 'none';
      noResults.style.display = 'none';

      faqItems.forEach(function (item) {
        item.style.display = '';
        if (item.classList.contains('active')) {
          const answer = item.querySelector('.faq-answer');
          answer.style.maxHeight = answer.scrollHeight + 'px';
        }
      });
      searchInput.focus();
    });
  }
});