const API = "../backend/index.php";
let searchTimer;

async function loadProducts() {
    const search = document.getElementById("search")?.value.trim() || "";
    const container = document.getElementById("products");
    container.innerHTML = '<div class="empty-state"><span class="spinner"></span><p>Loading products...</p></div>';

    try {
        const response = await fetch(`${API}/products?search=${encodeURIComponent(search)}`, { credentials: "include" });
        const json = await response.json();
        if (!json.success) throw new Error(json.message || "Unable to load products.");

        const products = json.data || [];
        if (!products.length) {
            container.innerHTML = '<div class="empty-state">No products found.</div>';
            return;
        }

        container.innerHTML = products.map(product => `
            <article class="card product-card">
                ${product.image ? `<img class="product-image" src="../uploads/products/${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" loading="lazy">` : '<div class="product-placeholder">No image</div>'}
                <div class="product-body">
                    <h3>${escapeHtml(product.name)}</h3>
                    <p class="product-description">${escapeHtml(product.description || "No description available.")}</p>
                    <p class="price">₹${Number(product.price).toFixed(2)}</p>
                    <div class="stock">${Number(product.stock) > 0 ? `${product.stock} in stock` : "Out of stock"}</div>
                    <div class="actions">
                        <button class="btn" ${Number(product.stock) < 1 ? "disabled" : ""} onclick="addToCart(${product.id}, this)">Add to Cart</button>
                    </div>
                </div>
            </article>
        `).join("");
    } catch (error) {
        container.innerHTML = `<div class="empty-state">${escapeHtml(error.message || "Could not connect to API.")}</div>`;
    }
}

async function addToCart(id, button) {
    setButtonLoading(button, true, "Adding...");
    try {
        const response = await fetch(`${API}/cart`, {
            method: "POST",
            credentials: "include",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ product_id: id, quantity: 1 })
        });
        const json = await response.json();
        showToast(json.message, json.success ? "success" : "error");
    } catch {
        showToast("Could not add product to cart.", "error");
    } finally {
        setButtonLoading(button, false);
    }
}

function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, char => ({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#039;"}[char]));
}

document.getElementById("search")?.addEventListener("input", () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(loadProducts, 300);
});

loadProducts();
