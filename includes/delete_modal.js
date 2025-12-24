
function openDeleteModal(actionUrl, itemId) {
    const form = document.getElementById('deleteForm');
    const input = document.getElementById('deleteItemId');

    form.action = actionUrl;
    input.value = itemId;

    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
