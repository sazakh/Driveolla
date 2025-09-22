document.addEventListener('DOMContentLoaded', () => {
  const carGrid = document.getElementById('carGrid');
  const notif = document.getElementById('notif');
  const logoutBtn = document.getElementById('logoutBtn');
  const trackBtn = document.getElementById('trackBtn');


  logoutBtn?.addEventListener('click', () => {
    window.location.href = 'Login.php';
  });

  trackBtn?.addEventListener('click', () => {
  console.log("Tombol tracking diklik");
  window.location.href = 'tracking_mobil.php';
  });


  // Load dan render data mobil
  async function loadCars() {
    try {
      const res = await fetch('ambil_mobil.php');
      if (!res.ok) {
        throw new Error('Failed to fetch data');
      }
      const cars = await res.json();
      renderCards(cars);
    } catch (err) {
      console.error('Error loadCars:', err);
      showNotif('Gagal memuat data mobil', 'red');
    }
  }

  // Render kartu mobil + form tambah
  function renderCards(cars) {
    carGrid.innerHTML = '';

    cars.forEach(car => {
      const formattedTarif = formatRupiah(car.tarif_per_hari);

      const card = document.createElement('div');
      card.className = 'car-card';
      card.innerHTML = `
        <img src="${car.gambar}" alt="${car.merk} ${car.tipe}" />
        <h3>${car.merk} ${car.tipe}</h3>
        <p>Tahun: ${car.tahun}</p>
        <p>Tarif: ${formattedTarif}</p>
        <p>Status: <strong style="color:${car.status === 'tersedia' ? 'green' : 'red'}">${car.status}</strong></p>
        <div class="detail-mobil">
          <p>👥 Kapasitas: <span>${car.kapasitas}</span></p>
          <p>⛽ Bensin: <span>${car.bensin}</span></p>
          <p>🚙 Tipe: <span>${car.tipe}</span></p>
          <p>⚙️ Transmisi: <span>${car.transmisi}</span></p>
          <p>🚗 Drive: <span>${car.drive}</span></p>
          <p>🪪 Driver restrictions: <span>${car.restriksi_driver}</span></p>
        </div>
        <div class="actions">
          <button class="edit-btn" data-id="${car.id_mobil}">Edit</button>
          <button class="delete-btn" data-id="${car.id_mobil}">Hapus</button>
        </div>
      `;
      carGrid.appendChild(card);
    });

    // Form Tambah
    const addCard = document.createElement('div');
    addCard.className = 'car-card add-card';
    addCard.innerHTML = `
      <form id="addForm" enctype="multipart/form-data">
        <input name="merk" type="text" placeholder="Merk" required />
        <input name="tipe" type="text" placeholder="Tipe" required />
        <input name="tahun" type="number" placeholder="Tahun" required />
        <input name="tarif_per_hari" type="number" step="0.01" placeholder="Tarif per Hari" required />
        <input name="kapasitas" type="text" placeholder="Kapasitas" />
        <input name="bensin" type="text" placeholder="Bensin" />
        <input name="transmisi" type="text" placeholder="Transmisi" />
        <input name="drive" type="text" placeholder="Drive" />
        <input name="restriksi_driver" type="text" placeholder="Driver Restriction" />
        <textarea name="detail_mobil" placeholder="Detail Mobil" required></textarea>
        <input name="image" type="file" accept="image/*" required />
        <select name="status" required>
          <option value="tersedia">Tersedia</option>
          <option value="disewa">Disewa</option>
        </select>
        <button type="submit">Tambah Mobil</button>
      </form>
    `;
    carGrid.appendChild(addCard);

    // Pasang event listener
    const form = addCard.querySelector('#addForm');
    form.addEventListener('input', updateDetailText);
    form.addEventListener('submit', addCar);

    carGrid.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', () => deleteCar(btn.dataset.id));
    });
    carGrid.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', () => startEdit(btn.dataset.id));
    });
  }

  // Update detail mobil di textarea
  function updateDetailText(e) {
    const form = e.target.form;
    const kapasitas = form.kapasitas.value || '-';
    const bensin = form.bensin.value || '-';
    const transmisi = form.transmisi.value || '-';
    const drive = form.drive.value || '-';
    const restriksi = form.restriksi_driver.value || '-';

    const detailText = `👥 Kapasitas: ${kapasitas}
    ⛽ Bensin: ${bensin}
    🚙 Tipe: ${form.tipe.value}
    ⚙️ Transmisi: ${transmisi}
    🚗 Drive: ${drive}
    🪪 Driver restrictions: ${restriksi}`;

    form.detail_mobil.value = detailText;
  }

  // Format Rupiah
  function formatRupiah(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
  }

  // Tambah mobil
  async function addCar(e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    try {
      const res = await fetch('simpan_mobil.php', { method: 'POST', body: fd });
      const msg = await res.text();
      if (msg.trim() === 'success') {
        showNotif('Mobil berhasil ditambahkan', 'green');
        form.reset();
        loadCars();
      } else {
        showNotif('Gagal: ' + msg, 'red');
      }
    } catch (err) {
      console.error('Error addCar:', err);
      showNotif('Error saat menyimpan', 'red');
    }
  }

  // Hapus mobil
  async function deleteCar(id) {
    if (!confirm('Yakin ingin menghapus?')) return;
    try {
      const res = await fetch('delete_mobil.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_mobil=${encodeURIComponent(id)}`
      });
      const msg = await res.text();
      showNotif(msg, msg.toLowerCase().includes('berhasil') ? 'green' : 'red');
      loadCars();
    } catch (err) {
      console.error('Error deleteCar:', err);
      showNotif('Error saat menghapus', 'red');
    }
  }

  // Mulai edit mobil
  function startEdit(id) {
    fetch(`ambil_mobil.php?id=${id}`)
      .then(r => r.json())
      .then(car => showEditForm(car))
      .catch(err => console.error(err));
  }

  function showEditForm(car) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `
      <div class="modal-content">
        <h2>Edit Mobil</h2>
        <form id="editForm" enctype="multipart/form-data">
          <input name="id_mobil" type="hidden" value="${car.id_mobil}" />
          <input name="merk" type="text" value="${car.merk}" required />
          <input name="tipe" type="text" value="${car.tipe}" required />
          <input name="tahun" type="number" value="${car.tahun}" required />
          <input name="tarif_per_hari" type="number" step="0.01" value="${car.tarif_per_hari}" required />
          <textarea name="detail_mobil" required>${car.detail_mobil ?? ''}</textarea>
          <select name="status" required>
            <option value="tersedia" ${car.status === 'tersedia' ? 'selected' : ''}>Tersedia</option>
            <option value="disewa" ${car.status === 'disewa' ? 'selected' : ''}>Disewa</option>
          </select>
          <input name="image" type="file" accept="image/*" />
          <button type="submit">Simpan</button>
          <button type="button" id="cancelEditBtn">Batal</button>
        </form>
      </div>
    `;
    document.body.appendChild(modal);

    document.getElementById('cancelEditBtn').addEventListener('click', () => modal.remove());
    document.getElementById('editForm').addEventListener('submit', e => saveEdit(e, modal));
  }

  async function saveEdit(e, modal) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    try {
      const res = await fetch('update_mobil.php', { method: 'POST', body: fd });
      const msg = await res.text();
      if (msg.trim() === 'success') {
        showNotif('Mobil berhasil diperbarui', 'green');
        loadCars();
        modal.remove();
      } else {
        showNotif('Gagal: ' + msg, 'red');
      }
    } catch (err) {
      console.error('Error saveEdit:', err);
      showNotif('Error saat memperbarui', 'red');
    }
  }

  // Notifikasi
  function showNotif(message, color) {
    notif.innerText = message;
    notif.style.color = color;
    notif.style.display = 'block';
    setTimeout(() => notif.style.display = 'none', 3000);
  }

  // Load initial cars
  loadCars();
});
