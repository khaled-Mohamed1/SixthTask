@extends('layouts.layout')


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Installments</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<style>
    .required:after {
        content: " *";
        color: red;
    }
</style>

<body style="font-family: Cairo; direction: rtl;">
    <br>
    <div class="container">
        <div class="row">
            <h3>صفحة الرئيسية للأقساط</h3>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a href="{{ route('loans') }}" class="btn btn-primary">القروض</a>
            </div>
        </nav>

        <div class="row installment_table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">قيمة القسط</th>
                        <th scope="col">تاريخ الاستحقاق</th>
                        <th scope="col">تاريخ الدفع</th>
                        <th scope="col">المبلغ المدفوع</th>
                        <th scope="col">حالة القسط</th>
                        <th scope="col">اضافة</th>
                        <th>عمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loan->installments->sortBy('due_date') as $key => $installment)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <td>{{ $installment->installment_amount }}</td>
                            <td>{{ $installment->due_date }}</td>
                            <td>{{ $installment->payment_date }}</td>
                            <td>{{ $installment->paid_amount }}</td>
                            <td
                                class="{{ $installment->installment_status == 'غير مسدد' ? 'text-danger' : 'text-success' }}">
                                {{ $installment->installment_status }}</td>
                            @if ($installment->installment_status == 'تم التسديد')
                                <td></td>
                                <td></td>
                            @else
                                <td>
                                    <a href="" class="btn btn-success peyment_installment"
                                        data-id="{{ $installment->id }}"
                                        data-installment_amount="{{ $installment->installment_amount }}"
                                        data-toggle="modal" data-target="#addPayment"><i
                                            class="las la-money-bill"></i></a>
                                </td>

                                <td>
                                    @if ($installment->created_at == $installment->updated_at)
                                        <a href="" data-toggle="modal" data-target="#editPayment"
                                            data-edit_id="{{ $installment->id }}"
                                            data-edit_installment_amount="{{ $installment->installment_amount }}"
                                            class="btn btn-primary edit_installment"><i class="las la-edit"></i></a>
                                    @else
                                    @endif

                                    <a href="" data-id="{{ $installment->id }}"
                                        class="btn btn-secondary delete_installment"><i
                                            class="las la-calendar-times"></i></a>
                                </td>
                            @endif


                        </tr>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center fs-3 text-danger">لا يوجد </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>


    {{-- ajax --}}

    @extends('ajax.installment_js')

    {{-- endajax --}}

    {{-- modal --}}

    @extends('modals.installments.payment')
    @extends('modals.installments.editpayment')

    {{-- endmodal --}}
</body>

</html>
