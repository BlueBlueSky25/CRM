function viewPipelineDetail(pipelineId) {
    fetch(`/pipeline/${pipelineId}`)
        .then(response => response.json())
        .then(data => {
            // Populate edit modal with data
            document.getElementById('editStageId').value = data.stage_id;
            document.getElementById('editCustomerName').value = data.customer_name;
            document.getElementById('editPhone').value = data.phone || '';
            document.getElementById('editEmail').value = data.email || '';
            document.getElementById('editValue').value = data.value || '';
            document.getElementById('editDescription').value = data.description || '';
            document.getElementById('editNotes').value = data.notes || '';
            
            // Update form action
            document.getElementById('pipelineEditForm').action = `/pipeline/${pipelineId}`;
            
            // Show modal
            document.getElementById('pipelineEditModal').style.display = 'flex';
        })
        .catch(error => console.error('Error:', error));
}