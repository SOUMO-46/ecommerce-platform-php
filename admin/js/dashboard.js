const API = "../backend/index.php";
async function loadDashboard() {
    const notice = document.getElementById("notice");
    try {
        const me = await (await fetch(`${API}/me`, { credentials: "include" })).json();
        if (!me.data || me.data.role !== "admin") { notice.textContent = "Access denied. Login with an admin account."; notice.classList.add("error"); return; }
        notice.textContent = `Signed in as ${me.data.email}`;
        const [products, orders, users] = await Promise.all([fetch(`${API}/products`, { credentials: "include" }), fetch(`${API}/orders`, { credentials: "include" }), fetch(`${API}/users`, { credentials: "include" })]);
        document.getElementById("productsCount").textContent = ((await products.json()).data || []).length;
        document.getElementById("ordersCount").textContent = ((await orders.json()).data || []).length;
        document.getElementById("usersCount").textContent = ((await users.json()).data || []).length;
    } catch { notice.textContent = "Could not load dashboard data."; notice.classList.add("error"); }
}
loadDashboard();
