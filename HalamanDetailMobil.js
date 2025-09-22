document.addEventListener("DOMContentLoaded", function () {
  const pickupDate = document.getElementById("pickupDateInput");
  const returnDate = document.getElementById("returnDateInput");
  const totalPriceElem = document.getElementById("totalPrice");
  const finalDaysElem = document.getElementById("finalDays");
  const totalHargaInput = document.getElementById("totalHargaInput");
  const bookingForm = document.getElementById("bookingForm");

  function calculateTotal() {
    const start = new Date(pickupDate.value);
    const end = new Date(returnDate.value);

    if (!isNaN(start) && !isNaN(end) && end > start) {
      const diffTime = Math.abs(end - start);
      const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      const total = days * tarifPerHari;

      finalDaysElem.textContent = days;
      totalPriceElem.textContent = 'Rp ' + total.toLocaleString('id-ID');
      totalHargaInput.value = total;
    } else {
      finalDaysElem.textContent = 0;
      totalPriceElem.textContent = 'Rp 0';
      totalHargaInput.value = 0;
    }
  }

  pickupDate.addEventListener('change', calculateTotal);
  returnDate.addEventListener('change', calculateTotal);

  bookingForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    calculateTotal();

    const total = parseInt(totalHargaInput.value);
    if (isNaN(total) || total <= 0) {
      alert("Harap pilih tanggal sewa yang valid.");
      return;
    }

    const formData = new FormData(bookingForm);

    try {
      // 1. Simpan pemesanan ke database
      const simpanRes = await fetch("pemesanan.php", {
        method: "POST",
        body: formData,
      });

      const simpanText = await simpanRes.text();
      if (!simpanRes.ok || simpanText.includes("Gagal")) {
        throw new Error("Gagal menyimpan pemesanan: " + simpanText);
      }
      console.log("Pemesanan disimpan:", simpanText);

      // 2. Lanjutkan ke pembayaran
      const bayarRes = await fetch("bayar.php", {
        method: "POST",
        body: formData,
      });

      const bayarData = await bayarRes.json();

      if (bayarData.token) {
        snap.pay(bayarData.token, {
          onSuccess: function (result) {
            alert("✅ Pembayaran berhasil!");
            console.log(result);
            // redirect kalau perlu
            // window.location.href = "riwayat.php";
          },
          onPending: function (result) {
            alert("⏳ Pembayaran sedang diproses.");
            console.log(result);
          },
          onError: function (result) {
            alert("❌ Pembayaran gagal!");
            console.log(result);
          },
          onClose: function () {
            alert("⚠️ Kamu menutup popup tanpa menyelesaikan pembayaran.");
          },
        });
      } else {
        alert("Gagal mendapatkan token pembayaran.");
        console.error(bayarData);
      }
    } catch (err) {
      alert("Terjadi kesalahan: " + err.message);
      console.error(err);
    }
  });
});
