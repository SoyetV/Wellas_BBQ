function handleLogin() {
  alert("Redirecting to login/signup page...");
}

function orderNow() {
  alert("Redirecting to order page...");
}


 function openModal(id) {
  document.getElementById(id).style.display = "flex"; // NOT block
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}


// Handle login form submission
function handleLogin(event) {
  event.preventDefault();

  const accountType = document.getElementById('account-type').value;

  if (accountType === "admin") {
    window.location.href = "dashboard.html";
  } else if (accountType === "user") {
    window.location.href = "user.html";
  } else {
    alert("Please select a valid account type.");
  }
}
function handleSignup(event) {
  event.preventDefault();

  const accountType = document.getElementById('signup-type').value;

  if (accountType === "admin") {
    window.location.href = "dashboard.html";
  } else if (accountType === "user") {
    window.location.href = "user.html";
  } else {
    alert("Please select a valid account type.");
  }
}

function switchTo(id) {
  // Close all modals first
  document.querySelectorAll('.modal').forEach(modal => {
    modal.style.display = 'none';
  });

  // Open the requested modal (loginModal or signupModal)
  document.getElementById(id).style.display = 'flex';
}

// Optional: close modal when clicking outside
window.onclick = function (event) {
  if (event.target.classList.contains('modal')) {
    event.target.style.display = "none";
  }
};
