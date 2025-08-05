<!-- Enhanced AJAX Handler Component -->
<script>
// Global AJAX Form Handler for Create/Update Operations
function setupAjaxForm(formSelector, options = {}) {
    $(document).ready(function() {
        $(formSelector).on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const originalBtnText = submitBtn.html();
            const isUpdate = form.find('input[name="_method"][value="PUT"]').length > 0;
            
            // Default options
            const config = {
                loadingTitle: isUpdate ? 'Memperbarui...' : 'Menyimpan...',
                loadingText: isUpdate ? 'Sedang memperbarui data' : 'Sedang menyimpan data',
                successTitle: 'Berhasil!',
                successText: isUpdate ? 'Data berhasil diperbarui' : 'Data berhasil disimpan',
                redirectUrl: null,
                customValidation: null, // Custom validation function
                customDataPreparation: null, // Custom data preparation function
                onSuccess: null,
                onError: null,
                onComplete: null,
                ...options
            };
            
            // Run custom validation if provided
            if (config.customValidation && !config.customValidation(form)) {
                return false;
            }
            
            // Show loading
            Swal.fire({
                title: config.loadingTitle,
                text: config.loadingText,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Disable submit button
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            
            // Prepare form data
            let formData;
            let contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
            let processData = true;
            
            // Use FormData if custom data preparation is needed or form has file uploads
            if (config.customDataPreparation || form.attr('enctype') === 'multipart/form-data') {
                formData = new FormData(form[0]);
                if (!formData.has('_token')) {
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                }
                
                // Call custom data preparation function
                if (config.customDataPreparation) {
                    config.customDataPreparation(form, formData);
                }
                
                contentType = false;
                processData = false;
            } else {
                formData = form.serialize();
                if (formData.indexOf('_token=') === -1) {
                    formData += '&_token=' + $('meta[name="csrf-token"]').attr('content');
                }
            }

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: processData,
                contentType: contentType,
                success: function(response) {
                    Swal.fire({
                        title: config.successTitle,
                        text: response.message || config.successText,
                        icon: 'success',
                        confirmButtonColor: '#4AC8EA'
                    }).then(() => {
                        if (config.onSuccess) {
                            config.onSuccess(response, form);
                        } else if (config.redirectUrl) {
                            window.location.href = config.redirectUrl;
                        } else if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            location.reload();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    let message = 'Terjadi kesalahan saat memproses data';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            message = Object.values(errors).flat().join(', ');
                        } else if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                    } else if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            message = errorResponse.message || message;
                        } catch (e) {
                            message = 'Terjadi kesalahan pada server';
                        }
                    }
                    
                    Swal.fire({
                        title: 'Error!',
                        text: message,
                        icon: 'error',
                        confirmButtonColor: '#dc3545'
                    });
                    
                    if (config.onError) {
                        config.onError(xhr, status, error, form);
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    if (config.onComplete) {
                        config.onComplete(form);
                    }
                }
            });
        });
    });
}

// Global AJAX Delete Handler
function setupAjaxDelete(buttonSelector, options = {}) {
    $(document).on('click', buttonSelector, function(e) {
        e.preventDefault();
        
        const button = $(this);
        const config = {
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            itemName: button.data('name') || button.data('item-name') || '',
            deleteUrl: button.data('url') || button.data('delete-url') || button.attr('href'),
            confirmText: 'Ya, Hapus!',
            cancelText: 'Batal',
            successRedirectUrl: null,
            onSuccess: null,
            onError: null,
            ...options
        };
        
        // Customize text if item name is provided
        if (config.itemName) {
            config.text = `Apakah Anda yakin ingin menghapus "${config.itemName}"?`;
        }
        
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: config.confirmText,
            cancelButtonText: config.cancelText
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses penghapusan data',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // AJAX Delete Request
                $.ajax({
                    url: config.deleteUrl,
                    type: 'POST',
                    data: {
                        '_method': 'DELETE',
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || 'Data berhasil dihapus',
                            icon: 'success',
                            confirmButtonColor: '#4AC8EA'
                        }).then(() => {
                            if (config.onSuccess) {
                                config.onSuccess(response, button);
                            } else if (config.successRedirectUrl) {
                                window.location.href = config.successRedirectUrl;
                            } else if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        let message = 'Terjadi kesalahan saat menghapus data';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.statusText) {
                            message += ': ' + xhr.statusText;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                        
                        if (config.onError) {
                            config.onError(xhr, status, error, button);
                        }
                    }
                });
            }
        });
    });
}

// AJAX Update Handler (for inline updates)
function setupAjaxUpdate(buttonSelector, options = {}) {
    $(document).on('click', buttonSelector, function(e) {
        e.preventDefault();
        
        const button = $(this);
        const originalText = button.html();
        
        const config = {
            updateUrl: button.data('url') || button.data('update-url') || button.attr('href'),
            updateData: button.data('update-data') || {},
            loadingText: '<i class="fas fa-spinner fa-spin"></i>',
            successMessage: 'Data berhasil diperbarui',
            onSuccess: null,
            onError: null,
            ...options
        };
        
        // Show loading on button
        button.prop('disabled', true).html(config.loadingText);
        
        $.ajax({
            url: config.updateUrl,
            type: 'POST',
            data: {
                ...config.updateData,
                '_method': 'PUT',
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Show success toast
                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message || config.successMessage,
                    icon: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                
                if (config.onSuccess) {
                    config.onSuccess(response, button);
                } else if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr, status, error) {
                let message = 'Terjadi kesalahan saat memperbarui data';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                
                if (config.onError) {
                    config.onError(xhr, status, error, button);
                }
            },
            complete: function() {
                // Restore button
                button.prop('disabled', false).html(originalText);
            }
        });
    });
}

// Bulk Delete Handler
function setupBulkDelete(buttonSelector, checkboxSelector, options = {}) {
    $(document).on('click', buttonSelector, function(e) {
        e.preventDefault();
        
        const selectedItems = $(checkboxSelector + ':checked');
        
        if (selectedItems.length === 0) {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Pilih minimal satu item untuk dihapus',
                icon: 'warning',
                confirmButtonColor: '#4AC8EA'
            });
            return;
        }
        
        const config = {
            title: 'Konfirmasi Hapus Massal',
            text: `Apakah Anda yakin ingin menghapus ${selectedItems.length} item yang dipilih?`,
            deleteUrl: $(this).data('url') || $(this).data('bulk-delete-url'),
            onSuccess: null,
            onError: null,
            ...options
        };
        
        Swal.fire({
            title: config.title,
            text: config.text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const ids = [];
                selectedItems.each(function() {
                    ids.push($(this).val());
                });
                
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses penghapusan data',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: config.deleteUrl,
                    type: 'POST',
                    data: {
                        'ids': ids,
                        '_method': 'DELETE',
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || `${ids.length} item berhasil dihapus`,
                            icon: 'success',
                            confirmButtonColor: '#4AC8EA'
                        }).then(() => {
                            if (config.onSuccess) {
                                config.onSuccess(response);
                            } else {
                                location.reload();
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        let message = 'Terjadi kesalahan saat menghapus data';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                        
                        if (config.onError) {
                            config.onError(xhr, status, error);
                        }
                    }
                });
            }
        });
    });
}
</script>
