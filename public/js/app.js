// Custom JavaScript for Joe Fitness Center Management System

// Setup CSRF token for Ajax requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Image Preview Function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            $(previewId).attr('src', e.target.result).show();
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Attach image preview to file inputs
$(document).ready(function() {
    // Avatar upload preview
    $('input[name="avatar"]').change(function() {
        const preview = $('#avatar-preview');
        if (preview.length) {
            previewImage(this, '#avatar-preview');
        } else {
            // Create preview element if doesn't exist
            const previewHtml = '<img id="avatar-preview" class="image-preview rounded mt-2" style="display:none;">';
            $(this).parent().append(previewHtml);
            previewImage(this, '#avatar-preview');
        }
    });
});

// Confirmation Dialog
function confirmDelete(message = 'Bạn có chắc chắn muốn xóa?') {
    return confirm(message);
}

// Delete Form Handler
$('.delete-form').submit(function(e) {
    if (!confirmDelete()) {
        e.preventDefault();
        return false;
    }
});

// Toggle Status Confirmation
$('.toggle-status-form').submit(function(e) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái?')) {
        e.preventDefault();
        return false;
    }
});

// Auto-hide alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});

// Number formatting
function formatCurrency(number) {
    return new Intl.NumberFormat('vi-VN', { 
        style: 'currency', 
        currency: 'VND' 
    }).format(number);
}

// Date formatting
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

// Form validation helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return false;
    }
    return true;
}

// Search with debounce
let searchTimeout;
function searchWithDebounce(callback, delay = 500) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(callback, delay);
}

// Initialize tooltips
$(document).ready(function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Initialize popovers
$(document).ready(function() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Sidebar collapse toggle
$('#sidebarToggle').click(function() {
    $('.sidebar').toggleClass('collapsed');
});

// Print function
function printDiv(divId) {
    const printContents = document.getElementById(divId).innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}

// Export table to CSV
function exportTableToCSV(filename, tableId) {
    const csv = [];
    const rows = document.querySelectorAll(`#${tableId} tr`);
    
    for (let i = 0; i < rows.length; i++) {
        const row = [];
        const cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            row.push(cols[j].innerText);
        }
        
        csv.push(row.join(','));
    }
    
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Loading overlay
function showLoading() {
    $('body').append('<div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0,0,0,0.5); z-index: 9999;"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>');
}

function hideLoading() {
    $('#loading-overlay').remove();
}

// Ajax form submit helper
function submitAjaxForm(formId, successCallback, errorCallback) {
    const form = $('#' + formId);
    const formData = new FormData(form[0]);
    
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            showLoading();
        },
        success: function(response) {
            hideLoading();
            if (successCallback) {
                successCallback(response);
            }
        },
        error: function(xhr) {
            hideLoading();
            if (errorCallback) {
                errorCallback(xhr);
            } else {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            }
        }
    });
}

console.log('Joe Fitness Center Management System - Loaded');
