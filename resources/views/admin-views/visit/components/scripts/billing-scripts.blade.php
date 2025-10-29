<!-- Billing JavaScript -->
<script>
    $(document).ready(function() {
        $('.edit-billing').click(function() {
            const button = $(this);
            const modal = $('#edit_billing');

            const billingId = button.data('id');
            const totalAmount = parseFloat(button.data('total-amount')) || 0;
            const amountPaid = parseFloat(button.data('amount-paid')) || 0;
            const discountedAmount = parseFloat(button.data('discounted-amount')) || 0;

            let amountLeft = totalAmount - amountPaid;

            if (discountedAmount > 0) {
                const totalAfterDiscount = parseFloat(button.data('total_after_discount')) ||
                    amountLeft;
                amountLeft = totalAfterDiscount - amountPaid;
            }

            modal.find('#billing_id').val(billingId);
            modal.find('#total_amount').text(totalAmount.toFixed(2));
            modal.find('#amount_paid').text(amountPaid.toFixed(2));
            modal.find('#amount_left_display').text(amountLeft.toFixed(2));
            modal.find('#amount_left').attr('max', amountLeft.toFixed(2)).val(amountLeft.toFixed(2));
            modal.find('#amount_left').val('');

            if (discountedAmount > 0) {
                modal.find('#discount_info').show();
                modal.find('#discounted_amount').text(discountedAmount.toFixed(2));
                modal.find('#amount_after_discount').text((totalAmount - discountedAmount - amountPaid)
                    .toFixed(2));
            } else {
                modal.find('#discount_info').hide();
                modal.find('#discounted_amount').text('0.00');
                modal.find('#amount_after_discount').text(amountLeft.toFixed(2));
            }
        });

        $('#edit_billing_form').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.visit.update_payment') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#edit_billing').modal('hide');
                    $('#edit_billing_form')[0].reset();
                    toastr.success('{{ translate('Payment updated successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 1000);
                }
            });
        });

        $('.cancel-or-refund').click(function() {
            var billingId = $(this).data('id');
            $('#billing_id_cancel').val(billingId);
            var totalAmount = $(this).data('total-amount');
            var amountPaid = $(this).data('amount-paid');
            var amountLeft = totalAmount - amountPaid;

            $('#cancel_or_refund_form h5:eq(0)').text('Total Amount: ' + totalAmount);
            $('#cancel_or_refund_form h5:eq(1)').text('Amount Received: ' + amountPaid);
            $('#cancel_or_refund_form h5:eq(2)').text('Amount Left: ' + amountLeft);

            var modal_title = $('#cancel_or_refund').find('.modal-title');
            var reason_label = $('#cancel_or_refund').find('.input-label');
            var reason_placeholder = $('#cancel_or_refund').find('#cancel_reason');
            if (amountPaid <= 0) {
                modal_title.text('Cancel Billing');
                reason_label.text('Reason for Cancelation');
                reason_placeholder.attr('placeholder', 'Enter Reason for Cancelation');
            } else {
                modal_title.text('Refund Payment');
                reason_label.text('Reason for Refund');
                reason_placeholder.attr('placeholder', 'Enter Reason for Refund');
            }
        });

        $('#cancel_or_refund_form').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.visit.cancel-or-refund') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#cancel_or_refund').modal('hide');
                    $('#cancel_or_refund_form')[0].reset();
                    toastr.success('{{ translate('Payment updated successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 1000);
                }
            });
        });
    });

    function formatCurrency(amount) {
        var currencySymbol = "{{ $currency_code }}";
        var currencyPosition = "{{ $currency_position }}";
        return currencyPosition === 'left' ? `${currencySymbol} ${amount}` : `${amount} ${currencySymbol}`;
    }

    function viewBillingDetails(billingData) {
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            return new Intl.DateTimeFormat('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }).format(new Date(dateString));
        }

        var modalBody = document.getElementById('billingDetailsContent');

        var content = `
<div class="p-3">
    <h4 class="text-primary mb-3"><i class="tio tio-receipt"></i> Billing Information</h4>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Patient:</strong> <span class="text-dark">${billingData.visit.patient ? billingData.visit.patient.full_name : 'N/A'}</span></p>
            <p><strong>Billing Date:</strong> <span class="text-secondary">${formatDate(billingData.bill_date)}</span></p>
            <p><strong>Total Amount:</strong> <span class="badge bg-dark text-white px-2 py-1"> ${formatCurrency(billingData.total_amount)}</span></p>
            <p><strong>Discount:</strong> <span class="badge bg-secondary text-white px-2 py-1"> ${formatCurrency(billingData.discount)}</span></p>
        </div>

        <div class="col-md-6">
            <p><strong>Amount Paid:</strong> <span class="badge bg-primary text-white px-2 py-1"> ${formatCurrency(billingData.amount_paid)}</span></p>
            <p><strong>Status:</strong>
                <span class="badge ${billingData.status === 'paid' ? 'bg-success' : (billingData.status === 'pending' || billingData.status === 'refunded' ||
                    billingData.status === 'canceled' ? 'bg-warning text-dark' : 'bg-danger')} px-2 py-1">
                    ${billingData.status.charAt(0).toUpperCase() + billingData.status.slice(1)}
                </span>
            </p>
            <p><strong>Created By:</strong> <span class="text-dark">${billingData.admin ? billingData.admin.f_name + ' ' + billingData.admin.l_name : 'N/A'}</span></p>
        </div>
    </div>
    <p><strong>Note:</strong> <span class="text-muted">${billingData.note ? billingData.note : 'No additional notes'}</span></p>
    ${billingData.is_canceled == 1 ? `
        <p><strong>Canceled/Refunded By:</strong> <span class="text-muted">${billingData.canceled_by_admin ? billingData.canceled_by_admin.f_name + ' ' + billingData.canceled_by_admin.l_name : 'N/A'}</span></p>
        <p><strong>Reason:</strong> <span class="text-muted">${billingData.cancel_reason ? billingData.cancel_reason : 'No additional notes'}</span></p>
    ` : ''}
    <hr class="my-3">

    <h5 class="text-secondary"><i class="tio tio-credit-card"></i> Payment History</h5>
    <ul class="list-group">
        ${billingData.payments && billingData.payments.length > 0 ?
            billingData.payments.map(payment => `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="tio tio-wallet"></i>Received ${payment.payment_method} - <strong>${formatCurrency(payment.amount_paid)}</strong></span>
                    <span class="text-muted">${payment.invoice_no || 'N/A'}</span>
                </li>
            `).join(''):''
        }
        ${billingData.is_canceled == 1 && billingData.status == 'refunded' ?
            `<li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="tio tio-wallet"></i> ${billingData.status} - <strong>${formatCurrency(billingData.amount_paid)}</strong></span>
                <span class="text-muted">By ${billingData.canceled_by_admin.f_name + ' ' + billingData.canceled_by_admin.l_name || 'N/A'}</span>
            </li>`:''}
        ${(billingData.payments && billingData.payments.length == 0 && (billingData.is_canceled == 0 || billingData.status == 'canceled')) ?
            '<li class="list-group-item text-muted">No payment history available</li>':''}
    </ul>
</div>
`;

        modalBody.innerHTML = content;
        $('#billingModal').modal('show');
    }
</script>
<script>
    $(document).ready(function() {
        $('#add_discount').on('show.bs.modal', function(e) {
            const button = $(e.relatedTarget);
            const modal = $(this);

            const totalAmount = parseFloat(button.attr('data-total-amount')) || 0;
            const amountPaid = parseFloat(button.attr('data-amount-paid')) || 0;
            const amountLeft = Math.max(totalAmount - amountPaid, 0);

            modal.find('#billing_id').val(button.attr('data-id'));
            modal.find('#total_amount').text(totalAmount.toFixed(2));
            modal.find('#amount_paid').text(amountPaid.toFixed(2));
            modal.find('#amount_left_display').text(amountLeft.toFixed(2));

            modal.find('#discount_type').val('');
            modal.find('#discount_value').val('');
            modal.find('#discounted_amount').text('0.00');
            modal.find('#amount_after_discount').text(amountLeft.toFixed(2));

            modal.find('#discount_type, #discount_value').off('input change').on('input change',
                function() {
                    const type = modal.find('#discount_type').val();
                    const val = parseFloat(modal.find('#discount_value').val());
                    let discount = 0;

                    if (!isNaN(val)) {
                        if (type === 'fixed') {
                            discount = val;
                        } else if (type === 'percent') {
                            discount = amountLeft * (val / 100);
                        }
                    }

                    discount = Math.min(discount, amountLeft);

                    modal.find('#discounted_amount').text(discount.toFixed(2));
                    modal.find('#amount_after_discount').text((amountLeft - discount).toFixed(2));
                });
        });

        $('#add_discount_form').on('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            const submitButton = $(this).find('button[type="submit"]');
            const originalText = disableButton(submitButton);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{ route('admin.visit.add-discount') }}',
                method: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#add_discount').modal('hide');
                    $('#add_discount_form')[0].reset();
                    toastr.success('{{ translate('Payment updated successfully!') }}', {
                        closeButton: true,
                        progressBar: true
                    });
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        toastr.error(xhr.responseJSON.error, {
                            closeButton: true,
                            progressBar: true
                        });
                    } else {
                        toastr.error(
                            '{{ translate('An error occurred while processing your request.') }}', {
                                closeButton: true,
                                progressBar: true
                            });
                    }
                },
                complete: function() {
                    setTimeout(function() {
                        enableButton(submitButton, originalText);
                    }, 1000);
                }
            });
        });
    });
</script>
