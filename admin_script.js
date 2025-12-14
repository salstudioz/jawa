document.addEventListener('DOMContentLoaded', function() {
    // Konfirmasi Hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if(!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // Form validation untuk admin
    const adminForms = document.querySelectorAll('form');
    adminForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const numberFields = this.querySelectorAll('input[type="number"]');
            let isValid = true;
            
            numberFields.forEach(field => {
                if(field.value && field.value < 0) {
                    e.preventDefault();
                    alert('Nilai tidak boleh negatif!');
                    field.focus();
                    field.style.borderColor = 'var(--danger-red)';
                    isValid = false;
                } else {
                    field.style.borderColor = '';
                }
            });
            
            // Validasi harga
            const hargaFields = this.querySelectorAll('input[name*="harga"]');
            hargaFields.forEach(field => {
                if(field.value && (field.value < 10000 || field.value > 100000000)) {
                    e.preventDefault();
                    alert('Harga harus antara 10.000 dan 100.000.000');
                    field.focus();
                    field.style.borderColor = 'var(--danger-red)';
                    isValid = false;
                }
            });
            
            return isValid;
        });
    });

    // Table row highlight on hover
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f0f9ff';
        });
        
        row.addEventListener('mouseleave', function() {
            if(this.classList.contains('even')) {
                this.style.backgroundColor = '#fafafa';
            } else {
                this.style.backgroundColor = '';
            }
        });
    });

    // Add alternating row colors
    const tbody = document.querySelector('tbody');
    if(tbody) {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            if(index % 2 === 0) {
                row.classList.add('even');
            }
        });
    }

    // Search functionality for tables
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Cari...';
    searchInput.className = 'form-control';
    searchInput.style.cssText = `
        max-width: 300px;
        margin-bottom: 20px;
    `;

    const actionBar = document.querySelector('.action-bar');
    if(actionBar) {
        // Cek apakah sudah ada search
        if(!document.querySelector('.search-box')) {
            const searchBox = document.createElement('div');
            searchBox.className = 'search-box';
            searchBox.appendChild(searchInput);
            actionBar.appendChild(searchBox);
            
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    }

    // Toggle form visibility
    window.toggleForm = function(formId) {
        const form = document.getElementById(formId);
        if(form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
            if(form.style.display === 'block') {
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }
    };

    // Confirm before leaving page with unsaved changes
    let formChanged = false;
    const formElements = document.querySelectorAll('form input, form select, form textarea');
    formElements.forEach(element => {
        element.addEventListener('change', () => {
            formChanged = true;
        });
    });

    window.addEventListener('beforeunload', (e) => {
        if(formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Submit form on Ctrl+Enter
    document.addEventListener('keydown', function(e) {
        if((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const activeForm = document.querySelector('form');
            if(activeForm) {
                activeForm.submit();
            }
        }
    });

    // Update status with confirmation
    window.updateStatus = function(id, status) {
        if(confirm(`Ubah status pesanan #${id} menjadi "${status}"?`)) {
            window.location.href = `?update_status&id=${id}&status=${status}`;
        }
    };

    // Print invoice
    window.printInvoice = function(orderId) {
        window.open(`print_invoice.php?id=${orderId}`, '_blank');
    };

    // Quick actions
    const quickActions = {
        markPaid: function(id) {
            if(confirm('Tandai pesanan sebagai lunas?')) {
                window.location.href = `?update_status&id=${id}&status=Lunas`;
            }
        },
        markPending: function(id) {
            if(confirm('Tandai pesanan sebagai pending?')) {
                window.location.href = `?update_status&id=${id}&status=Pending`;
            }
        },
        cancelOrder: function(id) {
            if(confirm('Batalkan pesanan ini?')) {
                window.location.href = `?update_status&id=${id}&status=Dibatalkan`;
            }
        }
    };

    // Expose quick actions to global scope
    window.quickActions = quickActions;

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[title]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.title;
            tooltip.style.cssText = `
                position: absolute;
                background: #333;
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
                z-index: 1000;
                max-width: 200px;
                word-wrap: break-word;
            `;
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            tooltip.style.left = (rect.left + rect.width/2 - tooltip.offsetWidth/2) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if(this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => {
                if(alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });

    // Responsive table scrolling
    const tables = document.querySelectorAll('.table-container');
    tables.forEach(table => {
        if(table.scrollWidth > table.clientWidth) {
            table.style.overflowX = 'auto';
            table.style.position = 'relative';
            
            // Add scroll indicator
            const indicator = document.createElement('div');
            indicator.innerHTML = '&larr; Scroll &rarr;';
            indicator.style.cssText = `
                text-align: center;
                color: #666;
                font-size: 12px;
                padding: 5px;
                background: #f5f5f5;
                border-radius: 0 0 5px 5px;
            `;
            table.parentNode.insertBefore(indicator, table.nextSibling);
        }
    });
});

// Utility functions
function formatCurrency(amount) {
    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

// Export utilities to global scope
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;