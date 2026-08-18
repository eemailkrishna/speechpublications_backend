<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="mb-0" id="deleteConfirmMessage">Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="deleteConfirmSubmit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        var pendingUrl = null;
        var pendingForm = null;

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-delete-url], [data-delete-form]');
            if (!btn) return;
            e.preventDefault();

            pendingUrl = null;
            pendingForm = null;

            if (btn.hasAttribute('data-delete-url')) {
                pendingUrl = btn.getAttribute('data-delete-url');
            }
            if (btn.hasAttribute('data-delete-form')) {
                pendingForm = document.getElementById(btn.getAttribute('data-delete-form'));
            }

            if (btn.getAttribute('data-message')) {
                document.getElementById('deleteConfirmMessage').textContent = btn.getAttribute('data-message');
            }

            new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
        });

        document.getElementById('deleteConfirmSubmit').addEventListener('click', function() {
            if (pendingForm) {
                pendingForm.submit();
            } else if (pendingUrl) {
                window.location.href = pendingUrl;
            }
        });
    })();
</script>
