<div class="modal fade" id="addPayment" tabindex="-1" role="dialog" aria- labelledby="addPaymentLabel" aria-hidden="true">


    <form action="" method="POST" id="addPayment_form">
        @csrf

        <input type="hidden" name="loan_id" id="loan_id" value="{{ $loan->id }}">

        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentLabel">اضافة مبلغ للقسط</h5>
                    <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body row">

                    <div class="errMsgContainer mb-3" id="errMsgContainer"></div>

                    <input name="installment_amount" id="installment_amount" type="hidden">
                    <input name="add_id" id="add_id" type="hidden">

                    <div class="col-md-6">
                        <label class="form-label required">مبلغ الدفع</label>
                        <input name="paid_amount" id="paid_amount" type="text" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">تاريخ الادخال</label>
                        <input name="payment_date" id="payment_date" type="text" class="form-control"
                            value="{{ now()->toDateTimeString() }}" disabled>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary add_Payment">اعتماد</button>
                </div>

            </div>
        </div>

    </form>
</div>
