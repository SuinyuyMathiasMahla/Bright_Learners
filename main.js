Main.js

document.addEventListener('DOMContentLoaded', () => {
    const feeForm = document.getElementById('feeForm');
    if (feeForm) {
        feeForm.addEventListener('submit', (e) => {
            const amount = parseFloat(feeForm.amount.value || '0');
            if (amount <= 0) {
                alert('Amount must be greater than 0.');
                e.preventDefault();
            }
        });
    }
});