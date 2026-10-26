const API = "../backend/index.php";
let editingId = null;

async function checkAdmin() {
    const response = await fetch(`${API}/me`, { credentials: "include" });
    const json = await response.json();
    if (!json.data || json.data.role !== "admin") {
        showToast("Admin login required.", "error");
        setTimeout(() => location.href = "../frontend/login.html", 700);
        return false;
    }
    return true;
}

async function loadProducts() {
    const list = document.getElementById("list");
    list.innerHTML = '<div class="empty-state">Loading products...</div>';
    try {
        const response = await fetch(`${API}/products`, { credentials: "include" });
        const json = await response.json();
        const products = json.data || [];
        if (!products.length) { list.innerHTML = '<div class="empty-state">No products found.</div>'; return; }
        list.innerHTML = `<div class="table-wrap"><table><thead><tr><th>ID</th><th>Product</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead><tbody>${products.map(product => `<tr><td>#${product.id}</td><td><b>${escapeHtml(product.name)}</b></td><td>₹${Number(product.price).toFixed(2)}</td><td>${product.stock}</td><td><button class="btn small" onclick="editProduct(${product.id})">Edit</button> <button class="btn danger small" onclick="deleteProduct(${product.id})">Delete</button></td></tr>`).join("")}</tbody></table></div>`;
    } catch { list.innerHTML = '<div class="empty-state">Could not load products.</div>'; }
}

async function editProduct(id) {
    const response = await fetch(`${API}/products/${id}`, { credentials: "include" });
    const json = await response.json();
    if (!json.success || !json.data) { showToast(json.message || "Product not found.", "error"); return; }
    const product = json.data;
    document.getElementById("name").value = product.name || "";
    document.getElementById("description").value = product.description || "";
    document.getElementById("price").value = product.price ?? "";
    document.getElementById("stock").value = product.stock ?? "";
    document.getElementById("category_id").value = product.category_id ?? "";
    editingId = id;
    document.getElementById("submitBtn").textContent = "Update Product";
    document.getElementById("cancelBtn").style.display = "inline-flex";
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function cancelEdit() {
    editingId = null;
    document.getElementById("form").reset();
    document.getElementById("submitBtn").textContent = "Add Product";
    document.getElementById("cancelBtn").style.display = "none";
}

document.getElementById("form").addEventListener("submit", async event => {
    event.preventDefault();
    const button = document.getElementById("submitBtn");
    setButtonLoading(button, true, editingId ? "Updating..." : "Saving...");
    const url = editingId ? `${API}/products/${editingId}` : `${API}/products`;
    try {
        const response = await fetch(url, { method: editingId ? "PUT" : "POST", credentials: "include", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ name: document.getElementById("name").value.trim(), description: document.getElementById("description").value.trim(), price: Number(document.getElementById("price").value), stock: Number(document.getElementById("stock").value), category_id: document.getElementById("category_id").value ? Number(document.getElementById("category_id").value) : null }) });
        const json = await response.json();
        showToast(json.message, json.success ? "success" : "error");
        if (json.success) { cancelEdit(); loadProducts(); }
    } catch { showToast("Could not save product.", "error"); }
    finally { setButtonLoading(button, false); }
});

document.getElementById("cancelBtn").addEventListener("click", cancelEdit);

async function deleteProduct(id) {
    if (!confirm("Delete this product?")) return;
    const response = await fetch(`${API}/products/${id}`, { method: "DELETE", credentials: "include" });
    const json = await response.json();
    showToast(json.message, json.success ? "success" : "error");
    if (json.success) loadProducts();
}

function escapeHtml(value) { return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char])); }

(async () => { if (await checkAdmin()) loadProducts(); })();
