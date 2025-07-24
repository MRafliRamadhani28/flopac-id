<style>
/* Shared table styling for all DataTables */
.swal-popup-poppins {
    font-family: 'Poppins', sans-serif !important;
}

/* Table transparent styling */
.table-transparent {
    background: transparent !important;
}

.table-transparent th,
.table-transparent td {
    background: transparent !important;
    border-bottom: 1px solid #eee;
    padding: 1rem 0.75rem;
}

.table-transparent thead th {
    border-bottom: 2px solid #eee;
}

/* Additional DataTable custom styling */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    font-family: 'Poppins', sans-serif;
    border-radius: 6px;
    margin: 0 2px;
    background: var(--color-foreground) !important;
    border: 1px solid var(--color-foreground) !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button a {
    background: var(--color-foreground) !important;
    border: 1px solid var(--color-foreground) !important;
    color: white !important;
    border-radius: 6px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #4AC8EA 0%, #4AC8EA 100%) !important;
    border: 1px solid #4AC8EA !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current a {
    background: linear-gradient(135deg, #4AC8EA 0%, #4AC8EA 100%) !important;
    border: 1px solid #4AC8EA !important;
    color: white !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    color: #495057 !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover a {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    color: #495057 !important;
}

/* Action button styling */
.btn-group .btn {
    background: var(--color-foreground) !important;
    border-color: var(--color-foreground) !important;
    color: white !important;
    transition: all 0.3s ease;
    margin-right: 8px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-group .btn:hover.btn-warning {
    background: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #000 !important;
}

.btn-group .btn:hover.btn-danger {
    background: #dc3545 !important;
    border-color: #dc3545 !important;
    color: white !important;
}

.btn-group .btn:hover.btn-primary {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.btn-group .btn:hover.btn-success {
    background: #198754 !important;
    border-color: #198754 !important;
    color: white !important;
}

.btn-group .btn:hover.btn-info {
    background: #0dcaf0 !important;
    border-color: #0dcaf0 !important;
    color: #000 !important;
}

.btn-group .btn:hover.btn-secondary {
    background: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
}

/* Custom search styling */
.custom-search-container {
    position: relative;
}

.custom-search-input {
    padding-left: 45px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.custom-search-input:focus {
    border-color: var(--color-foreground);
    box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.25);
}

.custom-search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    pointer-events: none;
}
</style>
