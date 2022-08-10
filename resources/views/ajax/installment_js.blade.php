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

        //show data of isntallment to add
        $(document).on('click', '.peyment_installment', function() {
            let installment_id = $(this).data('id');
            let installment_amount = $(this).data('installment_amount');
            $('#errMsgContainer').empty();

            $('#add_id').val(installment_id);
            $('#installment_amount').val(installment_amount);
        });


        //add payment to installment
        $(document).on('click', '.add_Payment', function(e) {
            e.preventDefault();
            let paid_amount = $('#paid_amount').val();
            let installment_id = $('#add_id').val();
            let installment_amount = $('#installment_amount').val();
            let loan_id = $('#loan_id').val();
            $('#errMsgContainer').empty();

            $.ajax({
                url: "{{ route('payment.installment') }}",
                method: 'post',
                data: {
                    paid_amount: paid_amount,
                    installment_amount: installment_amount,
                    installment_id: installment_id,
                    loan_id: loan_id,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#addPayment').modal('hide');
                        $('#addPayment_form')[0].reset();
                        $('#errMsgContainer').empty();
                        $('.installment_table').removeClass('row');
                        $('.installment_table').load(location.href + ' .installment_table');
                        Command: toastr["success"]("تم اضافة مبلغ للقسط بنجاح", "العملية")

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

        //show data of installment to edit
        $(document).on('click', '.edit_installment', function() {
            let edit_id = $(this).data('edit_id');
            let edit_installment_amount = $(this).data('edit_installment_amount');
            $('#errMsgContainer').empty();

            $('#edit_id').val(edit_id);
            $('#edit_installment_amount').val(edit_installment_amount);
        });

        //edit loan data
        $(document).on('click', '.edit_Payment', function(e) {
            e.preventDefault();
            let edit_id = $('#edit_id').val();
            let edit_installment_amount = $('#edit_installment_amount').val();
            $('#errMsgContainer').empty();

            $.ajax({
                url: "{{ route('editpayment.installment') }}",
                method: 'post',
                data: {
                    edit_id: edit_id,
                    edit_installment_amount: edit_installment_amount,
                },
                success: function(res) {
                    if (res.status == 'success') {
                        $('#editPayment').modal('hide');
                        $('#editPayment_form')[0].reset();
                        $('#errMsgContainer').empty();
                        $('.installment_table').removeClass('row');
                        $('.installment_table').load(location.href + ' .installment_table');
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
                    $('#errMsgContainer').empty();
                    $.each(error.errors, function(index, value) {
                        $(".errMsgContainer").append('<span class="text-danger">' +
                            value + '</span>' + '<br>');
                    });
                },
            });
        });

        //delete installment
        $(document).on('click', '.delete_installment', function(e) {
            e.preventDefault();
            let installment_id = $(this).data('id');
            if (confirm('هل تريد تأجيل هذا القسط!')) {
                $.ajax({
                    url: "{{ route('delete.installment') }}",
                    method: 'POST',
                    data: {
                        installment_id: installment_id,
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.installment_table').removeClass('row');
                            $('.installment_table').load(location.href +
                                ' .installment_table');
                            Command: toastr["success"]("تم تأجيل القسط بنجاح", "العملية")

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
