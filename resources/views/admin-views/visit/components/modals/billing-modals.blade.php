<!-- Billing Modals -->
<div class="modal fade" id="edit_billing" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Edit Billing') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="edit_billing_form">
                    @csrf
                    <input type="hidden" name="billing_id" id="billing_id" />
                    <input type="text" hidden name="received_by_id" value="{{ auth('admin')->user()->id }}">

                    <div>
                        <h5>Total Amount: <span id="total_amount"></span></h5>
                        <h5>Amount Received: <span id="amount_paid"></span></h5>
                        <h5>Amount Left: <span id="amount_left_display"></span></h5>

                        <!-- Discount info, only show if exists -->
                        <div id="discount_info" style="display:none;">
                            <h5>Discounted Amount: <span id="discounted_amount"></span></h5>
                            <h5>Amount after Discount: <span id="amount_after_discount"></span></h5>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <label class="input-label" for="fn_no">{{ translate('FS No') }}</label>
                        <input type="text" name="fn_no" id="fn_no" class="form-control"
                            placeholder="Enter FS No from receipt" />
                    </div>

                    <div class="form-group mt-4">
                        <label class="input-label" for="amount_left">{{ translate('Amount:') }}<span
                                class="input-label-secondary text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount_left" id="amount_left" class="form-control"
                            placeholder="Enter amount" required min="1" />
                    </div>

                    <div class="form-group mt-4">
                        <label class="input-label" for="payment_method">{{ translate('Payment Method:') }}<span
                                class="input-label-secondary text-danger">*</span></label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="cash">{{ translate('Cash') }}</option>
                            <option value="bank_transfer">{{ translate('Bank Transfer') }}</option>
                            <option value="wallet">{{ translate('Wallet') }}</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Add Discount') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="add_discount_form">
                    @csrf
                    <input type="hidden" name="billing_id" id="billing_id" />
                    <input type="text" hidden name="received_by_id" value="{{ auth('admin')->user()->id }}">

                    <div>
                        <h5>Total Amount: <span id="total_amount"></span></h5>
                        <h5>Amount Received: <span id="amount_paid"></span></h5>
                        <h5>Amount Left: <span id="amount_left_display"></span></h5>
                        <h5>Discounted Amount: <span id="discounted_amount"></span></h5>
                        <h5>Amount after Discount: <span id="amount_after_discount"></span></h5>
                    </div>
                    <div class="form-group mt-4">
                        <label for="discount_type">{{ translate('Discount Type') }}<span
                                class="input-label-secondary text-danger">*</span></label>
                        <select class="form-control" id="discount_type" name="discount_type">
                            <option value="=" disabled selected>Select Discount Type</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percent">Percentage</option>
                        </select>
                    </div>
                    <div class="form-group mt-4">
                        <label class="input-label" for="discount_value">{{ translate('discount_value:') }}<span
                                class="input-label-secondary text-danger">*</span></label>
                        <input type="number" step="0.01" name="discount_value" id="discount_value"
                            class="form-control" placeholder="Enter amount" required min="1" />
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancel_or_refund" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Cancel or Refund Billing') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="cancel_or_refund_form">
                    @csrf
                    <input type="hidden" name="billing_id" id="billing_id_cancel" />
                    <input type="text" hidden name="canceled_by" value="{{ auth('admin')->user()->id }}">

                    <div>
                        <h5>Total Amount: <span id="total_amount"></span></h5>
                        <h5>Amount Received: <span id="amount_paid"></span></h5>
                        <h5>Amount Left: <span id="amount_left_display_cancel"></span></h5>
                    </div>

                    <div class="form-group mt-4">
                        <label class="input-label"
                            for="cancel_reason">{{ translate('Reason for Cancel or Refund') }}</label>
                        <textarea name="cancel_reason" id="cancel_reason" class="form-control"
                            placeholder="Enter Reason for Cancel or Refund" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Billing Details Modal -->
<div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billingModalLabel">Billing Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="billingDetailsContent">
                <!-- Content will be injected here -->
            </div>
        </div>
    </div>
</div>
