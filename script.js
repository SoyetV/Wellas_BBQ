function handleLogin() {
  alert("Redirecting to login/signup page...");
}

function orderNow() {
  alert("Redirecting to order page...");
}


  function openModal(id) {
    document.getElementById(id).style.display = 'flex';
  }

  function closeModal(id) {
    document.getElementById(id).style.display = 'none';
  }

  function switchTo(id) {
    document.querySelectorAll('.modal').forEach(modal => modal.style.display = 'none');
    openModal(id);
  }

  window.onclick = function(e) {
    document.querySelectorAll('.modal').forEach(modal => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  };
