window.addEventListener('DOMContentLoaded', () => {
  const productList = document.getElementById('productList');
  const searchInput = document.getElementById('searchInput');

  // Ambil data produk dari ambil_mobil.php
  async function loadProducts() {
    try {
      const res = await fetch('ambil_mobil.php');
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const cars = await res.json();
      console.log('cars:', cars); // Debug
      renderProducts(cars);
      searchInput.addEventListener('input', filterProducts);
    } catch (err) {
      console.error(err);
      productList.innerHTML =
        `<p style="color:red;text-align:center;">Error: ${err.message}</p>`;
    }
  }

  // Render produk ke HTML
  function renderProducts(cars) {
    productList.innerHTML = '';
    if (!Array.isArray(cars) || cars.length === 0) {
      productList.innerHTML = '<p style="text-align:center;">Tidak ada mobil.</p>';
      return;
    }

    cars.forEach(car => {
      const card = document.createElement('div');
      card.className = 'card';
      card.dataset.name = `${car.merk} ${car.tipe}`;
      card.dataset.id = car.id_mobil; // Tambahkan ID mobil

      card.innerHTML = `
        <img src="${car.gambar}" alt="${car.merk} ${car.tipe}">
        <h2>${car.merk} ${car.tipe}</h2>
        <p class="price">${formatRupiah(car.tarif_per_hari)} / hari</p>
        <button class="promo-btn">Detail</button>
      `;
      productList.appendChild(card);
    });

    // Tambahkan event handler tombol Detail
    document.querySelectorAll('.promo-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        const c = e.target.closest('.card');
        const id = c.dataset.id;
        console.log("ID Mobil:", id); // Debugging
        window.location.href = `HalamanDetailMobil.php?id=${id}`;
      });
    });
  }

  // Filter produk berdasarkan input
  function filterProducts() {
    const q = searchInput.value.toLowerCase();
    document.querySelectorAll('.card').forEach(card => {
      card.style.display = card.dataset.name.toLowerCase().includes(q) ? '' : 'none';
    });
  }

  // Format ke Rupiah
  function formatRupiah(val) {
    const n = Number(val);
    if (isNaN(n)) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(n);
  }

  loadProducts();
});
