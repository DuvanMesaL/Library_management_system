// Biblioteca Digital - JavaScript Functions

document.addEventListener("DOMContentLoaded", () => {
  // Initialize all components
  initToasts()
  initFormValidation()
  initHoverEffects()
  initModalHandlers()

  console.log("🚀 Biblioteca Digital initialized successfully!")
})

// Toast Notifications
function initToasts() {
  const toasts = document.querySelectorAll(".toast")

  toasts.forEach((toast) => {
    // Auto-hide after 5 seconds
    setTimeout(() => {
      hideToast(toast)
    }, 5000)

    // Add click to dismiss
    toast.addEventListener("click", () => {
      hideToast(toast)
    })
  })
}

function hideToast(toast) {
  toast.style.opacity = "0"
  toast.style.transform = "translateX(100%)"
  setTimeout(() => {
    if (toast.parentNode) {
      toast.parentNode.removeChild(toast)
    }
  }, 300)
}

function showToast(message, type = "success") {
  const toast = document.createElement("div")
  toast.className = `toast toast-${type} animate-fade-in`

  const icon =
    type === "success"
      ? '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
      : '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'

  toast.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span>${message}</span>
        </div>
    `

  document.body.appendChild(toast)

  // Auto-hide
  setTimeout(() => hideToast(toast), 5000)

  // Click to dismiss
  toast.addEventListener("click", () => hideToast(toast))
}

// Form Validation
function initFormValidation() {
  const forms = document.querySelectorAll("form")

  forms.forEach((form) => {
    const inputs = form.querySelectorAll("input[required], select[required], textarea[required]")

    inputs.forEach((input) => {
      input.addEventListener("blur", () => validateField(input))
      input.addEventListener("input", () => clearFieldError(input))
    })

    form.addEventListener("submit", (e) => {
      if (!validateForm(form)) {
        e.preventDefault()
        showToast("Por favor, corrige los errores en el formulario", "error")
      }
    })
  })
}

function validateField(field) {
  const value = field.value.trim()
  const type = field.type
  let isValid = true
  let message = ""

  // Required validation
  if (field.hasAttribute("required") && !value) {
    isValid = false
    message = "Este campo es obligatorio"
  }

  // Email validation
  if (type === "email" && value && !isValidEmail(value)) {
    isValid = false
    message = "Ingresa un email válido"
  }

  // Password validation
  if (type === "password" && value && value.length < 8) {
    isValid = false
    message = "La contraseña debe tener al menos 8 caracteres"
  }

  // Password confirmation
  if (field.name === "password_confirmation") {
    const password = document.querySelector('input[name="password"]')
    if (password && value !== password.value) {
      isValid = false
      message = "Las contraseñas no coinciden"
    }
  }

  showFieldValidation(field, isValid, message)
  return isValid
}

function validateForm(form) {
  const inputs = form.querySelectorAll("input[required], select[required], textarea[required]")
  let isValid = true

  inputs.forEach((input) => {
    if (!validateField(input)) {
      isValid = false
    }
  })

  return isValid
}

function showFieldValidation(field, isValid, message) {
  // Remove existing error
  clearFieldError(field)

  if (!isValid) {
    field.classList.add("border-red-500", "bg-red-50")
    field.classList.remove("border-amber-300")

    const errorDiv = document.createElement("div")
    errorDiv.className = "field-error text-red-600 text-sm mt-1"
    errorDiv.textContent = message

    field.parentNode.appendChild(errorDiv)
  } else {
    field.classList.remove("border-red-500", "bg-red-50")
    field.classList.add("border-green-500", "bg-green-50")
  }
}

function clearFieldError(field) {
  field.classList.remove("border-red-500", "bg-red-50", "border-green-500", "bg-green-50")
  field.classList.add("border-amber-300")

  const existingError = field.parentNode.querySelector(".field-error")
  if (existingError) {
    existingError.remove()
  }
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

// Hover Effects
function initHoverEffects() {
  const hoverElements = document.querySelectorAll(".hover-lift")

  hoverElements.forEach((element) => {
    element.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-4px)"
    })

    element.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)"
    })
  })
}

// Modal Handlers
function initModalHandlers() {
  // Confirmation dialogs
  const deleteButtons = document.querySelectorAll("[data-confirm]")

  deleteButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      const message = this.getAttribute("data-confirm") || "¿Estás seguro?"
      if (!confirm(message)) {
        e.preventDefault()
      }
    })
  })
}

// Loading States
function showLoading(button) {
  const originalText = button.innerHTML
  button.setAttribute("data-original-text", originalText)
  button.disabled = true
  button.innerHTML = `
        <div class="spinner mr-2"></div>
        Cargando...
    `
}

function hideLoading(button) {
  const originalText = button.getAttribute("data-original-text")
  button.disabled = false
  button.innerHTML = originalText
}

// Utility Functions
function formatDate(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("es-ES", {
    year: "numeric",
    month: "long",
    day: "numeric",
  })
}

function formatDateTime(dateString) {
  const date = new Date(dateString)
  return date.toLocaleDateString("es-ES", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  })
}

function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Search functionality
function initSearch() {
  const searchInputs = document.querySelectorAll("[data-search]")

  searchInputs.forEach((input) => {
    const target = input.getAttribute("data-search")
    const searchableElements = document.querySelectorAll(target)

    const debouncedSearch = debounce((query) => {
      searchableElements.forEach((element) => {
        const text = element.textContent.toLowerCase()
        const matches = text.includes(query.toLowerCase())
        element.style.display = matches ? "" : "none"
      })
    }, 300)

    input.addEventListener("input", (e) => {
      debouncedSearch(e.target.value)
    })
  })
}

// Export functions for global use
window.LibraryApp = {
  showToast,
  showLoading,
  hideLoading,
  formatDate,
  formatDateTime,
  initSearch,
}
