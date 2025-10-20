const params = new URLSearchParams(window.location.search);
const id = params.get("id");
const API_URL = "http://localhost:9000/bookstore/api/sach.php";

async function loadBook() {
  const res = await fetch(`${API_URL}?MaSach=${id}`);
  const data = await res.json();

  if (data.error) {
    alert("Không tìm thấy sách!");
    window.location.href = "index.html";
    return;
  }

  document.getElementById("MaSach").value = data.MaSach;
  document.getElementById("TenSach").value = data.TenSach;
  document.getElementById("TacGia").value = data.TacGia;
  document.getElementById("NhaXB").value = data.NhaXB;
  document.getElementById("TheLoai").value = data.TheLoai;
  document.getElementById("GiaBan").value = data.GiaBan;
}

document.getElementById("editForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const book = {
    MaSach: document.getElementById("MaSach").value,
    TenSach: document.getElementById("TenSach").value,
    TacGia: document.getElementById("TacGia").value,
    NhaXB: document.getElementById("NhaXB").value,
    TheLoai: document.getElementById("TheLoai").value,
    GiaBan: document.getElementById("GiaBan").value
  };

  const res = await fetch(API_URL, {
    method: "PUT",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify(book)
  });

  const result = await res.json();
  alert(result.message || result.error);
  if (result.message) window.location.href = "index.html";
});

loadBook();
