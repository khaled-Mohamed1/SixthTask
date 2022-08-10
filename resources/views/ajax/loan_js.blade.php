<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {

        //add loan
        $(document).on('click', '.add_loan', function(e) {
            e.preventDefault();
            let name = $('#name').val();
            let loan_amount = $('#loan_amount').val();
            let currency = $('#currency').val();
            let date = $('#date').val();
            let loan_status = $('#loan_status').val();
            let installment_start_date = $('#installment_start_date').val();
            let installment_amount = $('#installment_amount').val();

            let number_of_installment = loan_amount / installment_amount;
            test_number_of_installment = Math.ceil(number_of_installment); //
            // console.log('عدد الأقساط: ' + number_of_installment);
            // console.log('عدد الأقساط بالكسور: ' + test_number_of_installment);

            let loan_rest = loan_amount - ((test_number_of_installment - 1) * installment_amount)
            // console.log('باقي القرض: ' + loan_rest);



            $.ajax({
                url: "{{ route('add.loan') }}",
                method: 'post',
                data: {
                    name: name,
                    loan_amount: loan_amount,
                    currency: currency,
                    date: date,
                    loan_status: loan_status,
                    installment_start_date: installment_start_date,
                    installment_amount: installment_amount,
                    // number_of_installment: number_of_installment,
                    test_number_of_installment: test_number_of_installment,
                    loan_rest: loan_rest,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#addLoan').modal('hide');
                        $('#addLoan_form')[0].reset();
                        $('#errMsgContainer').empty();
                        $('.loan_table').removeClass('row');
                        $('.loan_table').load(location.href + ' .loan_table');
                        Command: toastr["success"]("تم اضافة القرض بنجاح", "العملية")

                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-left",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                },
                error: function(err) {
                    $(".errMsgContainer").empty();
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        $(".errMsgContainer").append('<span class="text-danger">' +
                            value + '</span>' + '<br>');
                    });
                },
            });
        });

        //show loan value in edit form
        $(document).on('click', '.edit_loan', function() {
            let id = $(this).data('loan_id');
            let loan_amount = $(this).data('loan_amount');
            let loan_currency = $(this).data('loan_currency');
            let loan_name = $(this).data('loan_name');
            let installment_start_date = $(this).data('installment_start_date');
            let installment_amount = $(this).data('installment_amount');
            let loan_status = $(this).data('loan_status');

            $('#edit_id').val(id);
            $('#edit_loan_amount').val(loan_amount);
            $('#edit_currency').val(loan_currency);
            $('#edit_name').val(loan_name);
            $('#edit_loan_status').val(loan_status);
            $('#edit_installment_start_date').val(installment_start_date);
            $('#edit_installment_amount').val(installment_amount);
        });

        //edit loan data
        $(document).on('click', '.edit_Loan', function(e) {
            e.preventDefault();
            let edit_id = $('#edit_id').val();
            let edit_loan_amount = $('#edit_loan_amount').val();
            let edit_currency = $('#edit_currency').val();
            let edit_name = $('#edit_name').val();
            let edit_loan_status = $('#edit_loan_status').val();
            let edit_installment_start_date = $('#edit_installment_start_date').val();
            let edit_installment_amount = $('#edit_installment_amount').val();
            
            $.ajax({
                url: "{{ route('edit.loan') }}",
                method: 'post',
                data: {
                    edit_id: edit_id,
                    edit_currency: edit_currency,
                    edit_loan_amount: edit_loan_amount,
                    edit_name: edit_name,
                    edit_loan_status: edit_loan_status,
                    edit_installment_start_date: edit_installment_start_date,
                    edit_installment_amount: edit_installment_amount,

                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#editLoan').modal('hide');
                        $('#editLoan_form')[0].reset();
                        $('.loan_table').removeClass('row');
                        $('.loan_table').load(location.href + ' .loan_table');
                        Command: toastr["success"]("تم تعديل القرض بنجاح", "العملية")

                        toastr.options = {
                            "closeButton": true,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": true,
                            "positionClass": "toast-top-left",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                    }
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        $(".errMsgContainer").append('<span class="text-danger">' +
                            value + '</span>' + '<br>');
                    });
                },
            });
        });


        //delete loan
        $(document).on('click', '.delete_loan', function(e) {
            e.preventDefault();
            let loan_id = $(this).data('id');
            if (confirm('هل تريد حذف هذا القرض؟!')) {
                $.ajax({
                    url: "{{ route('delete.loan') }}",
                    method: 'POST',
                    data: {
                        loan_id: loan_id,
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.loan_table').removeClass('row');
                            $('.loan_table').load(location.href + ' .loan_table');
                            Command: toastr["success"]("تم حذف القرض بنجاح", "العملية")

                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-left",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    },
                });
            }
        });

    });
</script>
