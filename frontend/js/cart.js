const API = "../backend/index.php";

async function loadCart() {
    const container = document.getElementById("cart");
    container.innerHTML = '<div class="empty-state"><span class="spinner"></span><p>Loading cart...</p></div>';

    try {
        const response = await fetch(`${API}/cart`, { credentials: "include" });
        const json = await response.json();
        if (!json.success) throw new Error(json.message || "Unable to load cart.");

        const items = json.data || [];
        if (!items.length) {
            container.innerHTML = '<div class="empty-state">Your cart is empty.<br><br><a class="btn" href="products.html">Browse Products</a></div>';
            document.getElementById("cartSummary").style.display = "none";
            return;
        }

        let total = 0;
        container.innerHTML = items.map(item => {
            const subtotal = Number(item.subtotal);
            total += subtotal;
            return `<div class="card cart-item"><div style="display:flex;justify-content:space-between;gap:20px;align-items:center;flex-wrap:wrap"><div><h3 style="margin:0 0 5px">${escapeHtml(item.name)}</h3><span class="muted">₹${Number(item.price).toFixed(2)} × ${item.quantity}</span></div><strong>₹${subtotal.toFixed(2)}</strong><button class="btn danger small" onclick="removeItem(${item.id}, this)">Remove</button></div></div>`;
        }).join("");

        document.getElementById("cartTotal").textContent = `₹${total.toFixed(2)}`;
        document.getElementById("cartSummary").style.display = "flex";
    } catch (error) {
        container.innerHTML = `<div class="empty-state">${escapeHtml(error.message)}</div>`;
    }
}

async function removeItem(id, button) {
    setButtonLoading(button, true, "Removing...");
    try {
        const response = await fetch(`${API}/cart/${id}`, { method: "DELETE", credentials: "include" });
        const json = await response.json();
        showToast(json.message, json.success ? "success" : "error");
        if (json.success) loadCart();
    } catch {
        showToast("Could not remove item.", "error");
        setButtonLoading(button, false);
    }
}

async function checkout() {
    const button = document.getElementById("checkoutBtn");
    setButtonLoading(button, true, "Placing order...");
    try {
        const response = await fetch(`${API}/orders`, { method: "POST", credentials: "include" });
        const json = await response.json();
        showToast(json.message, json.success ? "success" : "error");
        if (json.success) setTimeout(() => location.href = "orders.html", 600);
    } catch {
        showToast("Could not place order.", "error");
        setButtonLoading(button, false);
    }
}

function escapeHtml(value) { return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char])); }
loadCart();
