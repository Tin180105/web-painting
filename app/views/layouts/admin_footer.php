        </main>

    </div>

    <div class="admin-modal-overlay" id="modal-overlay">
        <div class="admin-modal">
            <div class="admin-modal-header">
                <h2 id="modal-title">Thông tin</h2>
                <button type="button" class="admin-modal-close" id="modal-close" aria-label="Đóng">&times;</button>
            </div>
            <div class="admin-modal-body" id="modal-body"></div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <?php $adminJsPath = __DIR__ . "/../../../public/js/admin.js"; ?>
    <script src="<?= BASE_URL ?>/js/admin.js?v=<?= is_file($adminJsPath) ? filemtime($adminJsPath) : 1 ?>"></script>

</body>
</html>