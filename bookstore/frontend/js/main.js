// chỉnh lại API_URL cho đúng đường dẫn hosting của bạn
const API_URL = "http://localhost:9000/bookstore/api/sach.php";
const tableBody = document.getElementById("bookTable");
const searchInput = document.getElementById("search");

async function fetchAllBooks() {
  try {
    const res = await fetch(API_URL);
    if (!res.ok) throw new Error("HTTP error " + res.status);
    const data = await res.json();
    return data;
  } catch (err) {
    console.error("Lỗi khi fetch sách:", err);
    tableBody.innerHTML = `<tr><td colspan="7" class="text-danger">Không thể lấy dữ liệu: ${err.message}</td></tr>`;
    return [];
  }
}

function renderRows(list) {
  if (!Array.isArray(list) || list.length === 0) {
    tableBody.innerHTML = `<tr><td colspan="7" class="text-muted">Không có dữ liệu</td></tr>`;
    return;
  }
  tableBody.innerHTML = list.map(book => `
    <tr>
      <td>${book.MaSach}</td>
      <td>${book.TenSach || ''}</td>
      <td>${book.TacGia || ''}</td>
      <td>${book.GiaBan !== undefined ? book.GiaBan : ''}</td>
      <td>${book.TheLoai || ''}</td>
      <td>${book.NXB || ''}</td>
      <td>
        <a href="edit.html?id=${book.MaSach}" class="btn btn-warning btn-sm">Sửa</a>
        <button class="btn btn-danger btn-sm" onclick="deleteBook(${book.MaSach})">Xóa</button>
      </td>
    </tr>
  `).join("");
}

async function loadBooks() {
  const data = await fetchAllBooks();
  renderRows(data);
}

async function deleteBook(id) {
  if (!confirm("Bạn có chắc muốn xóa sách này?")) return;
  try {
    const res = await fetch(`${API_URL}?MaSach=${id}`, { method: "DELETE" });
    const r = await res.json();
    alert(r.message || r.error || "Xong");
    loadBooks();
  } catch (err) {
    alert("Lỗi khi xóa: " + err.message);
  }
}

searchInput?.addEventListener("input", async () => {
  const q = searchInput.value.trim().toLowerCase();
  const data = await fetchAllBooks();
  const filtered = data.filter(b => (b.TenSach || '').toLowerCase().includes(q));
  renderRows(filtered);
});

loadBooks();
