function calculateTotal() {
    const maleCount = document.getElementById('maleCount').value || 0;
    const femaleCount = document.getElementById('femaleCount').value || 0;
    const totalCount = parseInt(maleCount) + parseInt(femaleCount);
    document.getElementById('totalCount').value = totalCount;
}