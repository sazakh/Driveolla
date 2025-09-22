document.addEventListener('DOMContentLoaded', () => {
  const logoutBtn = document.getElementById('logoutBtn');

  // Cek apakah ada data pengguna yang sedang login di localStorage
  if (localStorage.getItem('loggedInUser')) {
    console.log('User sedang login');
  } else {
    console.log('User tidak ada dalam localStorage');
  }

  // Event listener untuk tombol logout
  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      // Hapus data pengguna dari localStorage
      localStorage.removeItem('loggedInUser');
      
      // Menampilkan log untuk konfirmasi
      console.log('User logout berhasil');
      
      // Alihkan pengguna ke halaman login
      window.location.href = 'Login.php'; // Pastikan Login.php ada
    });
  } else {
    console.log('Tombol logout tidak ditemukan');
  }
});
