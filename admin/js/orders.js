const API = "../backend/index.php";
async function load() {
    const me = await (await fetch(`${API}/me`, { credentials: "include" })).json();
    if (!me.data || me.data.role !== "admin") { showToast("Admin login required.", "error"); setTimeout(() => location.href = "../frontend/login.html", 700); return; }
    const json = await (await fetch(`${API}/orders`, { credentials: "include" })).json();
    const orders = json.data || [];
    document.getElementById("list").innerHTML = orders.length ? `<div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead><tbody>${orders.map(order => `<tr><td>#${order.id}</td><td>${escapeHtml(order.customer_name)}</td><td>₹${Number(order.total_amount).toFixed(2)}</td><td><select onchange="updateStatus(${order.id}, this.value)">${["pending","confirmed","shipped","delivered","cancelled"].map(status => `<option value="${status}" ${order.status === status ? "selected" : ""}>${status}</option>`).join("")}</select></td></tr>`).join("")}</tbody></table></div>` : '<div class="empty-state">No orders found.</div>';
}
async function updateStatus(id, status) {
    const response = await fetch(`${API}/orders/${id}`, { method: "PUT", credentials: "include", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ status }) });
    const json = await response.json();
    showToast(json.message, json.success ? "success" : "error");
    if (json.success) load();
}
function escapeHtml(value) { return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char])); }
load();
