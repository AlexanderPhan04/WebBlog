// Custom JavaScript cho WebBlog

// Auto dismiss alerts after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert:not(.alert-info)");

  alerts.forEach(function (alert) {
    setTimeout(function () {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });
});

// Confirm delete actions
function confirmDelete(message) {
  return confirm(message || "Bạn có chắc muốn xóa?");
}

// Smooth scroll to comments
if (window.location.hash) {
  const element = document.querySelector(window.location.hash);
  if (element) {
    setTimeout(function () {
      element.scrollIntoView({ behavior: "smooth", block: "center" });
      element.style.backgroundColor = "#fff3cd";
      setTimeout(function () {
        element.style.backgroundColor = "";
      }, 2000);
    }, 100);
  }
}

// Character counter for textarea (optional)
const textareas = document.querySelectorAll("textarea[data-max-length]");
textareas.forEach(function (textarea) {
  const maxLength = textarea.getAttribute("data-max-length");
  const counter = document.createElement("small");
  counter.className = "text-muted";
  textarea.parentNode.appendChild(counter);

  function updateCounter() {
    const remaining = maxLength - textarea.value.length;
    counter.textContent = `${remaining} ký tự còn lại`;

    if (remaining < 0) {
      counter.classList.add("text-danger");
      counter.classList.remove("text-muted");
    } else {
      counter.classList.remove("text-danger");
      counter.classList.add("text-muted");
    }
  }

  textarea.addEventListener("input", updateCounter);
  updateCounter();
});

// Form validation helper
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (form) {
    form.addEventListener("submit", function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add("was-validated");
    });
  }
}

// Initialize tooltips if Bootstrap tooltips are used
const tooltipTriggerList = document.querySelectorAll(
  '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
  (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

console.log("WebBlog initialized successfully!");
