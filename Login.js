document.getElementById('loginForm').addEventListener('submit', function (e) {
  e.preventDefault(); // Mencegah form disubmit secara biasa

  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value;
  const errorMessage = document.getElementById('loginError');

  // Validasi input
  if (!username || !password) {
    errorMessage.textContent = 'Username dan password harus diisi!';
    return;
  }

  const formData = new FormData();
  formData.append('username', username);
  formData.append('password', password);
  formData.append('action', 'Login'); // Kirim informasi login

  // Kirim data menggunakan fetch ke Login.php
  fetch('Login.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json()) // Mengambil hasil JSON dari server
  .then(data => {
    console.log("Data response:", data); // Untuk debugging

    if (data.success) {
      window.location.href = data.redirect; // Arahkan sesuai dengan role
    } else {
      errorMessage.textContent = data.message || 'Login gagal.'; // Menampilkan pesan error
    }
  })
  .catch(error => {
    console.error('Error:', error);
    errorMessage.textContent = 'Terjadi kesalahan jaringan.'; // Menampilkan error jika fetch gagal
  });

  
});
