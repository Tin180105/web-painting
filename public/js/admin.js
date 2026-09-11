document.addEventListener("DOMContentLoaded", () => {

    const overlay = document.getElementById("modal-overlay");
    const modalBody = document.getElementById("modal-body");
    const modalTitle = document.getElementById("modal-title");

    if (!overlay) return;

    function openModal() {
        overlay.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeModal() {
        overlay.classList.remove("show");
        document.body.style.overflow = "";
        modalBody.innerHTML = "";
    }

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) closeModal();
    });

    document.getElementById("modal-close").addEventListener("click", closeModal);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && overlay.classList.contains("show")) closeModal();
    });

    function showToast(message, isError = false) {
        const toast = document.getElementById("toast");
        if (!toast) return;

        toast.textContent = message;
        toast.className = "toast show" + (isError ? " toast-error" : "");

        setTimeout(() => { toast.className = "toast"; }, 2500);
    }

    // Thay nội dung bảng danh sách bằng bản mới lấy được sau khi Thêm/Sửa/Xóa thành công
    function swapListContainer(html) {
        const doc = new DOMParser().parseFromString(html, "text/html");
        const newContainer = doc.getElementById("admin-list-container");
        const currentContainer = document.getElementById("admin-list-container");

        if (newContainer && currentContainer) {
            currentContainer.innerHTML = newContainer.innerHTML;
        }
    }

    function bindFormSubmit(sourceNode) {
        const form = sourceNode.querySelector("form");
        if (!form) return;

        const cancelLink = form.querySelector(".admin-form-back");
        if (cancelLink) {
            cancelLink.addEventListener("click", (e) => {
                e.preventDefault();
                closeModal();
            });
        }

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitBtn = form.querySelector(".admin-form-submit");
            const originalLabel = submitBtn ? submitBtn.textContent : "";

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = "Đang lưu...";
            }

            try {
                const res = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form)
                });

                const html = await res.text();

                if (res.redirected) {
                    // Lưu thành công (server đã redirect về trang danh sách)
                    swapListContainer(html);
                    closeModal();
                    showToast("Đã lưu thành công");
                } else {
                    // Còn lỗi validate -> hiện lại form kèm thông báo lỗi ngay trong modal
                    const doc = new DOMParser().parseFromString(html, "text/html");
                    const newSource = doc.querySelector(".admin-modal-source");

                    if (newSource) {
                        modalBody.innerHTML = "";
                        modalBody.appendChild(newSource);
                        bindFormSubmit(newSource);
                    } else if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalLabel;
                    }
                }
            } catch (err) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalLabel;
                }
                showToast("Có lỗi xảy ra, vui lòng thử lại", true);
            }
        });
    }

    async function loadFormIntoModal(url, title) {
        modalTitle.textContent = title;
        modalBody.innerHTML = '<p class="modal-loading">Đang tải...</p>';
        openModal();

        try {
            const res = await fetch(url);
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, "text/html");
            const source = doc.querySelector(".admin-modal-source");

            if (source) {
                modalBody.innerHTML = "";
                modalBody.appendChild(source);
                bindFormSubmit(source);
            } else {
                modalBody.innerHTML = "<p>Không tải được nội dung.</p>";
            }
        } catch (err) {
            modalBody.innerHTML = "<p>Có lỗi xảy ra, vui lòng thử lại.</p>";
        }
    }

    function showConfirm(message, onConfirm) {
        modalTitle.textContent = "Xác nhận";
        modalBody.innerHTML = `
            <div class="modal-confirm">
                <p>${message}</p>
                <div class="modal-confirm-actions">
                    <button type="button" class="btn-cancel-confirm">Hủy</button>
                    <button type="button" class="btn-danger-confirm">Xóa</button>
                </div>
            </div>
        `;
        openModal();

        modalBody.querySelector(".btn-cancel-confirm").addEventListener("click", closeModal);
        modalBody.querySelector(".btn-danger-confirm").addEventListener("click", async () => {
            closeModal();
            await onConfirm();
        });
    }

    async function handleDelete(url) {
        showConfirm("Bạn có chắc muốn xóa mục này? Hành động này không thể hoàn tác.", async () => {
            try {
                const res = await fetch(url);
                const html = await res.text();
                swapListContainer(html);
                showToast("Đã xóa thành công");
            } catch (err) {
                showToast("Có lỗi xảy ra, vui lòng thử lại", true);
            }
        });
    }

        async function handleConfirmAction(url, message, successMessage) {
        showConfirm(message, async () => {
            try {
                const res = await fetch(url);
                const html = await res.text();

                if (document.getElementById("admin-list-container")) {
                    swapListContainer(html);
                    showToast(successMessage);
                } else {
                    // Trang chi tiết (không có list để swap) -> reload lại
                    window.location.reload();
                }
            } catch (err) {
                showToast("Có lỗi xảy ra, vui lòng thử lại", true);
            }
        });
    }
    // Dùng event delegation để vẫn hoạt động kể cả khi bảng danh sách được thay mới
    document.addEventListener("click", (e) => {
        const addOrEditLink = e.target.closest("[data-modal-form]");
        if (addOrEditLink) {
            e.preventDefault();
            loadFormIntoModal(addOrEditLink.href, addOrEditLink.dataset.modalTitle || "Thông tin");
            return;
        }

        const deleteLink = e.target.closest("[data-modal-delete]");
        if (deleteLink) {
            e.preventDefault();
            handleDelete(deleteLink.href);
        }

        const confirmActionLink = e.target.closest("[data-confirm-action]");
        if (confirmActionLink) {
            e.preventDefault();
            handleConfirmAction(
                confirmActionLink.href,
                confirmActionLink.dataset.confirmMessage || "Bạn có chắc muốn thực hiện hành động này?",
                confirmActionLink.dataset.successMessage || "Đã cập nhật thành công"
            );
            return;
        }
    });
});