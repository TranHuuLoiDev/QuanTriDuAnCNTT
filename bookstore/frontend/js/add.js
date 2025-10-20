document.getElementById("addForm").addEventListener("submit", async (e) => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());

  const res = await fetch("http://localhost:9000/bookstore/api/sach.php", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify(data)
  });

  const result = await res.json();
  alert(result.message || result.error);
  if (result.message) window.location.href = "index.html";
});
