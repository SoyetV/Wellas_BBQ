function openModal(id) {
  document.getElementById(id).style.display = "flex";
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// Switch between login and signup modals
function switchTo(id) {
  document.querySelectorAll('.modal').forEach(modal => {
    modal.style.display = 'none';
  });
  document.getElementById(id).style.display = 'flex';
}

// Handle login form submission
function handleLogin(event) {
  event.preventDefault();

  const accountType = document.getElementById('account-type').value;

  if (accountType === "admin") {
    window.location.href = "dashboard.html";
  } else if (accountType === "user") {
    window.location.href = "menu.html";
  } else {
    alert("Please select a valid account type.");
  }
}

// ✅ Handle signup form submission (moved outside)
function handleSignup(event) {
  event.preventDefault();

  const accountType = document.getElementById('signup-type').value;

  if (accountType === "admin") {
    window.location.href = "dashboard.html";
  } else if (accountType === "user") {
    window.location.href = "menu.html";
  } else {
    alert("Please select a valid account type.");
  }
}

// Optional: close modal when clicking outside
window.onclick = function (event) {
  if (event.target.classList.contains('modal')) {
    event.target.style.display = "none";
  }
};
