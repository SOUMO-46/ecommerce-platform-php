const API = "../backend/index.php";
async function load() {
    const me = await (await fetch(`${API}/me`, { credentials: "include" })).json();
    if (!me.data || me.data.role !== "admin") { showToast("Admin login required.", "error"); setTimeout(() => location.href = "../frontend/login.html", 700); return; }
    const json = await (await fetch(`${API}/users`, { credentials: "include" })).json();
    const users = json.data || [];
    document.getElementById("list").innerHTML = users.length ? `<div class="table-wrap"><table><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr></thead><tbody>${users.map(user => `<tr><td>#${user.id}</td><td>${escapeHtml(user.name)}</td><td>${escapeHtml(user.email)}</td><td>${escapeHtml(user.role)}</td></tr>`).join("")}</tbody></table></div>` : '<div class="empty-state">No users found.</div>';
}
function escapeHtml(value) { return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char])); }
load();
