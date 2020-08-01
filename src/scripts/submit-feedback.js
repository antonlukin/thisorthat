(function () {
  const popup = document.querySelector('.feedback');


  // Close feedback popup
  const closeFeedback = (e) => {
    if (e.keyCode === 27) {
      popup.classList.remove('feedback--visible');

      // Reset location hash
      history.replaceState(null, null, ' ');

      // Remove event listener
      document.removeEventListener('keydown', closeFeedback);
    }
  }


  // Show feedback popup
  const showFeedback = () => {
    popup.classList.add('feedback--visible');

    // Close popup on ESC key
    document.addEventListener('keydown', closeFeedback);
  }


  // Form submit handler
  const form = popup.querySelector('.feedback-form');

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Create message from textarea
    let message = form.querySelector('textarea').value;

    // Try to add concat if exists
    let contact = form.querySelector('input').value;

    if (contact) {
      message = `Контакт: ${contact}\n` + message;
    }

    let request = new XMLHttpRequest();
    request.open('POST', form.dataset.url + message);

    request.onload = () => {
      // Check if successfully sent
      if (request.status === 200) {
        return popup.classList.add('feedback--success');
      }

      return popup.classList.add('feedback--error');
    }

    request.send();
  });


  // Close button listener
  const close = popup.querySelector('.feedback-close');

  close.addEventListener('click', (e) => {
    e.preventDefault();

    popup.classList.remove('feedback--visible');

    // Reset location hash
    history.replaceState(null, null, ' ');
  });


  // Capture all feedback links
  document.querySelectorAll('a[href="#feedback"]').forEach(link => {
    link.addEventListener('click', () => {
      showFeedback();
    });
  });


  // Check if we need to show popup on load
  if (document.location.hash === '#feedback') {
    showFeedback();
  }
})();