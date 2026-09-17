// ==== Helper dùng chung ====

// Gọi API dạng POST, tự gửi kèm dữ liệu dạng form-urlencoded, trả về JSON
async function postForm(url, data) {
    const params = new URLSearchParams(data);

    const response = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: params
    });

    return response.json();
}

// Hiện thông báo nhỏ góc màn hình (thay cho alert() gây khó chịu)
function showToast(message, isError = false) {
    const toast = document.getElementById("toast");

    if (!toast) {
        return;
    }

    toast.textContent = message;
    toast.className = "toast show" + (isError ? " toast-error" : "");

    setTimeout(() => {
        toast.className = "toast";
    }, 2500);
}

// Cập nhật số badge giỏ hàng trên navbar
function updateCartBadge(count) {
    const badge = document.getElementById("cart-badge");

    if (badge) {
        badge.textContent = count;
    }
}

function formatCurrency(amount) {
    return Math.round(amount).toLocaleString("vi-VN") + " đ";
}

// ==== Thêm vào giỏ hàng (trang chủ + trang chi tiết) ====

document.querySelectorAll("[data-add-to-cart]").forEach((button) => {
    button.addEventListener("click", async () => {

        const paintingId = button.dataset.paintingId;

        let quantity = 1;
        const qtyInputId = button.dataset.quantityInput;

        if (qtyInputId) {
            const qtyInput = document.getElementById(qtyInputId);
            quantity = parseInt(qtyInput.value, 10) || 1;
        }

        button.disabled = true;

        try {

            const result = await postForm(window.BASE_URL + "/cart/add", {
                painting_id: paintingId,
                quantity: quantity
            });

            if (result.success) {
                showToast(result.message);
                updateCartBadge(result.cartCount);
            } else {
                showToast(result.message, true);
            }

        } catch (err) {
            showToast("Có lỗi xảy ra, vui lòng thử lại", true);
        } finally {
            button.disabled = false;
        }
    });
});

// Nút +/- số lượng ở trang chi tiết sản phẩm (chưa gửi API, chỉ đổi giá trị input)
document.querySelectorAll("[data-qty-increase]").forEach((btn) => {
    btn.addEventListener("click", () => {
        const input = btn.parentElement.querySelector("input");
        const max = parseInt(input.max, 10) || 9999;
        input.value = Math.min(max, parseInt(input.value, 10) + 1);
    });
});

document.querySelectorAll("[data-qty-decrease]").forEach((btn) => {
    btn.addEventListener("click", () => {
        const input = btn.parentElement.querySelector("input");
        input.value = Math.max(1, parseInt(input.value, 10) - 1);
    });
});

// ==== Trang chi tiết sản phẩm: chọn ảnh trong thumbnail gallery ====

document.querySelectorAll("[data-detail-thumb]").forEach((thumb) => {
    thumb.addEventListener("click", () => {
        const mainImg = document.getElementById("detail-main-img");
        if (!mainImg) return;

        mainImg.src = thumb.src;

        document.querySelectorAll("[data-detail-thumb]").forEach((t) => t.classList.remove("active"));
        thumb.classList.add("active");
    });
});

// ==== Trang giỏ hàng: đổi số lượng / xóa sản phẩm ====

document.querySelectorAll("[data-cart-row]").forEach((row) => {

    const cartDetailId = row.dataset.cartDetailId;
    const qtyInput = row.querySelector("[data-cart-qty-input]");
    const unitPrice = parseFloat(row.querySelector("[data-unit-price]").dataset.unitPrice);

    async function syncQuantity(newQuantity) {

        const result = await postForm(window.BASE_URL + "/cart/update", {
            cart_detail_id: cartDetailId,
            quantity: newQuantity
        });

        if (!result.success) {
            showToast(result.message, true);
            return;
        }

        if (result.removed) {
            row.remove();
            showToast("Đã xóa sản phẩm khỏi giỏ hàng");
        } else {
            row.querySelector("[data-subtotal]").textContent = formatCurrency(result.subtotal);
        }

        document.getElementById("cart-total").textContent = formatCurrency(result.total);
        updateCartBadge(result.cartCount);

        // Nếu giỏ hàng trống hẳn -> reload lại trang để hiện thông báo "giỏ hàng trống"
        if (result.cartCount === 0) {
            setTimeout(() => window.location.reload(), 600);
        }
    }

    row.querySelector("[data-cart-qty-increase]")?.addEventListener("click", () => {
        const max = parseInt(qtyInput.max, 10) || 9999;
        const newVal = Math.min(max, parseInt(qtyInput.value, 10) + 1);
        qtyInput.value = newVal;
        syncQuantity(newVal);
    });

    row.querySelector("[data-cart-qty-decrease]")?.addEventListener("click", () => {
        const newVal = Math.max(1, parseInt(qtyInput.value, 10) - 1);
        qtyInput.value = newVal;
        syncQuantity(newVal);
    });

    qtyInput?.addEventListener("change", () => {
        const max = parseInt(qtyInput.max, 10) || 9999;
        let newVal = parseInt(qtyInput.value, 10) || 1;
        newVal = Math.min(max, Math.max(1, newVal));
        qtyInput.value = newVal;
        syncQuantity(newVal);
    });

    row.querySelector("[data-cart-remove]")?.addEventListener("click", async () => {

        if (!confirm("Xóa sản phẩm này khỏi giỏ hàng?")) {
            return;
        }

        const result = await postForm(window.BASE_URL + "/cart/delete", {
            cart_detail_id: cartDetailId
        });

        if (result.success) {
            row.remove();
            showToast(result.message);
            document.getElementById("cart-total").textContent = formatCurrency(result.total);
            updateCartBadge(result.cartCount);

            if (result.cartCount === 0) {
                setTimeout(() => window.location.reload(), 600);
            }
        } else {
            showToast(result.message, true);
        }
    });
});

// ==== Trang địa chỉ: xóa / đặt mặc định ====

document.querySelectorAll("[data-address-row]").forEach((card) => {

    const addressId = card.dataset.addressId;

    card.querySelector("[data-address-delete]")?.addEventListener("click", async () => {

        if (!confirm("Bạn có chắc muốn xóa địa chỉ này?")) {
            return;
        }

        const result = await postForm(window.BASE_URL + "/addresses/delete", {
            address_id: addressId
        });

        if (result.success) {
            card.remove();
            showToast(result.message);
        } else {
            showToast(result.message, true);
        }
    });

    card.querySelector("[data-set-default]")?.addEventListener("click", async () => {

        const result = await postForm(window.BASE_URL + "/addresses/set-default", {
            address_id: addressId
        });

        if (result.success) {
            showToast(result.message);
            // Đơn giản và chắc chắn nhất: load lại trang để badge "Mặc định" cập nhật đúng
            window.location.reload();
        } else {
            showToast(result.message, true);
        }
    });
});
