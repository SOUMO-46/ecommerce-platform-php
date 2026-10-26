const API = "../backend/index.php";

async function checkAdmin() {
    const response = await fetch(`${API}/me`, { credentials: "include" });
    const json = await response.json();
    if (!json.data || json.data.role !== "admin") { showToast("Admin login required.", "error"); setTimeout(() => location.href = "../frontend/login.html", 700); return false; }
    return true;
}

async function load() {
    const list = document.getElementById("list");
    list.innerHTML = '<div class="empty-state">Loading categories...</div>';
    const response = await fetch(`${API}/categories`, { credentials: "include" });
    const json = await response.json();
    const categories = json.data || [];
    list.innerHTML = categories.length ? `<div class="card">${categories.map(category => `<div class="item-row"><div><b>#${category.id}</b> &nbsp; ${escapeHtml(category.name)}</div><div><button class="btn small" onclick="updateCategory(${category.id}, '${escapeJs(category.name)}')">Edit</button> <button class="btn danger small" onclick="deleteCategory(${category.id})">Delete</button></div></div>`).join("")}</div>` : '<div class="empty-state">No categories found.</div>';
}

document.getElementById("form").addEventListener("submit", async event => {
    event.preventDefault();
    const button = event.target.querySelector("button");
    setButtonLoading(button, true, "Adding...");
    try {
        const response = await fetch(`${API}/categories`, { method: "POST", credentials: "include", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ name: document.getElementById("name").value.trim() }) });
        const json = await response.json();
        showToast(json.message, json.success ? "success" : "error");
        if (json.success) { event.target.reset(); load(); }
    } finally { setButtonLoading(button, false); }
});

async function updateCategory(id, oldName) {
    const name = prompt("Enter new category name:", oldName)?.trim();
    if (!name) return;
    const response = await fetch(`${API}/categories/${id}`, { method: "PUT", credentials: "include", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ name }) });
    const json = await response.json();
    showToast(json.message, json.success ? "success" : "error");
    if (json.success) load();
}

async function deleteCategory(id) {
    if (!confirm("Delete this category?")) return;
    const response = await fetch(`${API}/categories/${id}`, { method: "DELETE", credentials: "include" });
    const json = await response.json();
    showToast(json.message, json.success ? "success" : "error");
    if (json.success) load();
}
function escapeHtml(value) { return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char])); }
function escapeJs(value) { return String(value).replace(/\\/g, "\\\\").replace(/'/g, "\\'").replace(/\r?\n/g, " "); }
(async () => { if (await checkAdmin()) load(); })();
