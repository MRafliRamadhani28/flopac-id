<style>
    /* === FLOPAC CONSISTENT FORM STYLING === */
    
    /* Form Card Styling */
    .form-card {
        background: var(--color-background);
        border-radius: 16px;
        border: 0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .form-card .card-body {
        padding: 2rem;
    }

    /* Input Styling */
    .minimal-input {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .minimal-input:focus {
        border-color: var(--color-foreground);
        box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
        background: #ffffff;
        outline: none;
    }

    .minimal-input:hover:not(:focus) {
        border-color: #dee2e6;
    }

    /* Form Floating Labels */
    .form-floating > .minimal-input {
        padding: 1.625rem 1rem 0.625rem;
    }

    .form-floating > label {
        padding: 1rem;
        color: var(--color-foreground);
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--color-foreground);
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    /* Select Styling */
    .minimal-select-container {
        position: relative;
    }

    .minimal-label {
        font-size: 14px;
        font-weight: 500;
        color: var(--color-foreground);
        margin-bottom: 0.5rem;
        display: block;
        transition: all 0.3s ease;
    }

    .minimal-select {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1rem;
        font-size: 14px;
        background: #ffffff;
        color: var(--color-foreground);
        transition: all 0.3s ease;
        width: 100%;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .minimal-select:focus {
        border-color: var(--color-foreground);
        box-shadow: 0 0 0 0.2rem rgba(74, 200, 234, 0.15);
        outline: none;
    }

    .minimal-select:hover:not(:focus) {
        border-color: #dee2e6;
    }

    /* Textarea Styling */
    textarea.minimal-input {
        resize: vertical;
        min-height: 120px;
    }

    /* Button Styling */
    .minimal-btn-primary {
        background: var(--color-foreground);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 500;
        font-size: 14px;
        color: var(--color-background);
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .minimal-btn-primary:hover {
        background: linear-gradient(135deg, #39b8d6 0%, #39b8d6 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px var(--color-foreground);
        color: #ffffff;
        text-decoration: none;
    }

    .minimal-btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px var(--color-foreground);
    }

    .minimal-btn-primary:disabled {
        background: #b0d8e9;
        color: #ffffff;
        box-shadow: none;
    }

    .minimal-btn-secondary {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 2rem;
        font-weight: 500;
        font-size: 14px;
        color: #6c757d;
        background: #ffffff;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .minimal-btn-secondary:hover {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .minimal-btn-secondary:active {
        transform: translateY(0);
        background: #e9ecef;
    }

    /* Outline buttons */
    .btn-outline-secondary {
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        border-color: #e9ecef;
    }

    .btn-outline-secondary:hover {
        transform: translateY(-1px);
        border-color: #dee2e6;
    }

    .btn-outline-danger {
        border-radius: 12px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        transform: translateY(-1px);
    }

    /* Form validation styling */
    .minimal-input.is-invalid,
    .minimal-select.is-invalid {
        border-color: #dc3545;
    }

    .minimal-input.is-invalid:focus,
    .minimal-select.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
    }

    .invalid-feedback {
        font-size: 12px;
        margin-top: 0.25rem;
        color: #dc3545;
    }

    /* Form row spacing */
    .form-row {
        margin-bottom: 1.5rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-card .card-body {
            padding: 1.5rem;
        }
        
        .minimal-input,
        .minimal-select {
            padding: 0.875rem;
        }
        
        .form-floating > .minimal-input {
            padding: 1.375rem 0.875rem 0.5rem;
        }
        
        .minimal-btn-primary,
        .minimal-btn-secondary {
            padding: 0.625rem 1.5rem;
            font-size: 13px;
        }
    }

    /* Header styling consistency */
    .page-header h4 {
        color: var(--color-foreground);
        font-weight: 600;
        margin-bottom: 0;
    }

    .page-header p {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 0;
    }

    /* Icon button consistency */
    .btn i {
        margin-right: 8px;
        width: 20px;
        height: 20px;
    }

    /* Form section spacing */
    .form-section {
        margin-top: 2rem;
    }

    .form-section:first-child {
        margin-top: 0;
    }

    /* Button group consistency */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f3f4;
    }

    @media (max-width: 576px) {
        .form-actions {
            flex-direction: column-reverse;
            gap: 0.75rem;
        }
        
        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
