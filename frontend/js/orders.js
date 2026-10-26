const API = "../backend/index.php";

async function loadOrders() {
    const container = document.getElementById("orders");
    try {
        const response = await fetch(`${API}/orders`, { credentials: "include" });
        const json = await response.json();
        if (!json.success) throw new Error(json.message || "Unable to load orders.");
        const orders = json.data || [];
        if (!orders.length) {
            container.innerHTML = '<div class="empty-state">No orders found.<br><br><a class="btn" href="products.html">Start Shopping</a></div>';
            return;
        }
        container.innerHTML = orders.map(order => `<article class="card order-card"><div style="display:flex;justify-content:space-between;gap:15px;align-items:center;flex-wrap:wrap"><div><h3 style="margin:0">Order #${order.id}</h3><small class="muted">${escapeHtml(order.created_at || "")}</small></div><strong>₹${Number(order.total_amount).toFixed(2)}</strong></div><p>Status: <b>${escapeHtml(order.status)}</b></p></article>`).join("");
    } catch (error) {
        container.innerHTML = `<div class="empty-state">${escapeHtml(error.message)}</div>`;
    }
}
function escapeHtml(value) { return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char])); }
loadOrders();
