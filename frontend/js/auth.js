const API = "../backend/index.php";

async function submitAuthForm(event, type) {
    event.preventDefault();
    const form = event.currentTarget;
    const button = form.querySelector("button[type=submit], button:not([type])");
    const msg = document.getElementById("msg");
    setButtonLoading(button, true, type === "login" ? "Signing in..." : "Creating account...");
    msg.style.display = "block";
    msg.className = "message";
    msg.textContent = "Please wait...";

    const payload = type === "login"
        ? { email: document.getElementById("email").value.trim(), password: document.getElementById("password").value }
        : { name: document.getElementById("name").value.trim(), email: document.getElementById("email").value.trim(), password: document.getElementById("password").value };

    try {
        const response = await fetch(`${API}/${type === "login" ? "login" : "register"}`, { method: "POST", headers: { "Content-Type": "application/json" }, credentials: "include", body: JSON.stringify(payload) });
        const json = await response.json();
        msg.textContent = json.message || "Request completed.";
        msg.className = `message ${json.success ? "success" : "error"}`;
        if (json.success) {
            if (type === "login") setTimeout(() => location.href = json.data?.role === "admin" ? "../admin/index.html" : "products.html", 500);
            else setTimeout(() => location.href = "login.html", 700);
        }
    } catch {
        msg.textContent = "Could not connect to the server.";
        msg.className = "message error";
    } finally {
        setButtonLoading(button, false);
    }
}

document.getElementById("loginForm")?.addEventListener("submit", event => submitAuthForm(event, "login"));
document.getElementById("registerForm")?.addEventListener("submit", event => submitAuthForm(event, "register"));
