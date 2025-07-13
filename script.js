// Open and Close Modal
function openModal(id) {
  document.getElementById(id).style.display = "flex";
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

// Switch Modal Views
function switchTo(id) {
  document.querySelectorAll('.modal').forEach(modal => {
    modal.style.display = 'none';
  });
  document.getElementById(id).style.display = 'flex';
}

// Handle Sign Up
function handleSignup(event) {
  event.preventDefault();
  const name = document.getElementById("signup-name").value;
  const email = document.getElementById("signup-email").value;
  const password = document.getElementById("signup-password").value;
  const accountType = document.getElementById("signup-type").value;

  if (!name || !email || !password || !accountType) {
    alert("All fields are required.");
    return;
  }

  const users = JSON.parse(localStorage.getItem("users")) || [];

  if (users.find(user => user.email === email)) {
    alert("User already exists.");
    return;
  }

  const newUser = { name, email, password, accountType };
  users.push(newUser);
  localStorage.setItem("users", JSON.stringify(users));

  alert("Account created. Please log in.");
  closeModal('signupModal');
  openModal('loginModal');
}

// Handle Login
function handleLogin(event) {
  event.preventDefault();
  const email = document.getElementById("login-email").value;
  const password = document.getElementById("login-password").value;
  const accountType = document.getElementById("account-type").value;

  const users = JSON.parse(localStorage.getItem("users")) || [];
  const matchedUser = users.find(user => user.email === email && user.password === password && user.accountType === accountType);

  if (!matchedUser) {
    alert("Invalid credentials or account type.");
    return;
  }

  localStorage.setItem("isLoggedIn", "true");
  localStorage.setItem("accountType", matchedUser.accountType);
  localStorage.setItem("loggedInUser", JSON.stringify({ name: matchedUser.name, email: matchedUser.email }));

  if (accountType === "admin") {
    window.location.href = "dashboard.html";
  } else {
    window.location.href = "menu.html";
  }
}

// Optional: Close modals when clicking outside
window.onclick = function (event) {
  if (event.target.classList.contains('modal')) {
    event.target.style.display = "none";
  }
};

// Protect Admin Pages
window.addEventListener("DOMContentLoaded", () => {
  const path = window.location.pathname;

  const protectedAdminPages = ["dashboard.html", "adminorder.html", "admin.html", "inventory.html", "report.html"];
  const isAdminPage = protectedAdminPages.some(p => path.endsWith(p));
  const accountType = localStorage.getItem("accountType");
  const isLoggedIn = localStorage.getItem("isLoggedIn");

  if (isAdminPage) {
    if (isLoggedIn !== "true" || accountType !== "admin") {
      alert("Access denied. Please log in as admin.");
      window.location.href = "index.html";
    }
  }
});
