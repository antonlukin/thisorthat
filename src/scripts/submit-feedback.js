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

    // Set form button disabled
    let button = form.querySelector('.feedback-submit');
    button.setAttribute('disabled', 'disabled');
    button.textContent = '…';

    let message = [];

    // Append textarea value
    message.push(form.querySelector('textarea').value);

    // Try to add concat if exists
    let contact = form.querySelector('input').value;

    if (contact) {
      message.push(`<b>${contact}</b>`);
    }

    let payload = {text: message.join("\n\n")};

    // Create AJAX request
    let request = new XMLHttpRequest();
    request.open('POST', '/help/');
    request.setRequestHeader('Content-Type', 'application/json');

    request.onload = () => {
      if (request.status === 200) {
        popup.classList.add('feedback--sent');

        return setTimeout(() => {
          popup.querySelector('.feedback-close').click();
        }, 2500);
      }

      popup.classList.add('feedback--error');
    }

    request.onerror = () => {
      popup.classList.add('feedback--error');
    }

    request.send(JSON.stringify(payload));
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
