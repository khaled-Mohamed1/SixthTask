<div class="modal fade" id="editPayment" tabindex="-1" role="dialog" aria- labelledby="editPaymentLabel" aria-hidden="true">


    <form action="" method="POST" id="editPayment_form">
        @csrf

        <input type="hidden" name="edit_loan_id" id="edit_loan_id" value="{{ $loan->id }}">

        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPaymentLabel">تعديل قيمة القسط</h5>
                    <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body row">

                    <div class="errMsgContainer mb-3" id="errMsgContainer"></div>

                    <input name="edit_id" id="edit_id" type="hidden">

                    <div class="col-md-6">
                        <label class="form-label required">قيمة القسط</label>
                        <input name="edit_installment_amount" id="edit_installment_amount" type="text"
                            class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary edit_Payment">اعتماد</button>
                </div>

            </div>
        </div>

    </form>
</div>
