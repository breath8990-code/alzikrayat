(() => {
  'use strict';

  document.querySelectorAll('.needs-validation').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });

  document.querySelectorAll('.toggle-password').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      const input = document.getElementById(button.getAttribute('data-target'));
      if (!input) return;
      const icon = button.querySelector('i');
      const isHidden = input.getAttribute('type') === 'password';
      input.setAttribute('type', isHidden ? 'text' : 'password');
      button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
      button.setAttribute('title', isHidden ? 'Hide password' : 'Show password');
      if (icon) {
        icon.classList.toggle('bi-eye', !isHidden);
        icon.classList.toggle('bi-eye-slash', isHidden);
      }
    });
  });
})();
