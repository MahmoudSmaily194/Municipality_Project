<div id="deleteModal" onclick="closeDeleteModal()">
    <div class="modal-content">
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete this item?</p>

        <form method="POST" id="deleteForm">
            <input type="hidden" name="id" id="deleteItemId">
            <button type="submit" class="delete-btn">Delete</button>
            <button type="button" class="cancel-btn" onclick="closeDeleteModal()">Cancel</button>
        </form>
    </div>
</div>
