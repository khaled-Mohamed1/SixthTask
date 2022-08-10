<div class="modal fade" id="editLoan" tabindex="-1" role="dialog" aria- labelledby="editLoanLabel" aria-hidden="true">


    <form action="" method="POST" id="editLoan_form">
        @csrf

        <input type="hidden" id="edit_id">


        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLoanLabel">تعديل قرض</h5>
                    <button type="button" class="close" data-dismiss="modal" aria- label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body row">

                    <div class="errMsgContainer mb-3" id="errMsgContainer"></div>


                    <div class="col-md-4">
                        <label class="form-label required">اسم الموظف</label>
                        <input name="edit_name" id="edit_name" type="text" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">قيمة القرض</label>
                        <input name="edit_loan_amount" id="edit_loan_amount" type="text" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">نوع العملة</label>
                        <select name="edit_currency" id="edit_currency" class="form-select">
                            <option selected disabled value="">Choose...</option>
                            <option value="شيكل">شيكل</option>
                            <option value="دولار">دولار</option>
                            <option value="دينار">دينار</option>
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label required">تاريخ الادخال</label>
                        <input name="date" id="date" type="text" class="form-control"
                            value="{{ now()->toDateTimeString() }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">حالة القرض</label>
                        <select name="edit_loan_status" id="edit_loan_status" class="form-select">
                            <option selected disabled value="">Choose...</option>
                            <option value="غير مسدد">غير مسدد</option>
                            <option value="مسدد جزئي">مسدد جزئي</option>
                            <option value="مسدد كامل">مسدد كامل</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">تاريخ بداية الأقساط</label>
                        <input name="edit_installment_start_date" id="edit_installment_start_date" type="date"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">مبلغ القسط</label>
                        <input name="edit_installment_amount" id="edit_installment_amount" type="text"
                            class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary edit_Loan">اعتماد</button>
                </div>

            </div>
        </div>

    </form>
</div>
