@extends('layouts.layout')


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loan</title>
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
            <h3>صفحة الرئيسية للقروض</h3>
        </div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLoan">اضافة
                </button>
                <button type="button" class="btn btn-info" id="button_export">استرداد ملفات
                </button>
            </div>
        </nav>

        <div class="row" id="export_excel" style="display: none">
            <div class="card bg-light" style="width: 100%">
                <div class="card-body">
                    <h5 class="card-title">استرداد ملف</h5>

                    <form action="{{ route('import.installment') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="input-group">
                                <input name="import_file" type="file" class="form-control" id="inputGroupFile04"
                                    aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                <button class="btn btn-outline-secondary" type="submit"
                                    id="inputGroupFileAddon04">استرداد</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="row loan_table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رقم المعرف</th>
                        <th scope="col">الاسم الموظف</th>
                        <th scope="col">قيمة القرض</th>
                        <th scope="col">العملة</th>
                        <th scope="col">التاريخ الإدخال</th>
                        <th scope="col">حالة القرض</th>
                        <th scope="col">تاريخ بداية الأقساط</th>
                        <th scope="col">مبلغ القسط</th>
                        <th scope="col">عرض</th>
                        <th>عمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $key => $loan)
                        <tr>
                            <th scope="row">{{ ++$key }}</th>
                            <th>{{ $loan->id }}</th>
                            <td>{{ $loan->name }}</td>
                            <td>{{ $loan->loan_amount }}</td>
                            <td>{{ $loan->currency }}</td>
                            <td>{{ $loan->date }}</td>
                            <td class="{{ $loan->loan_status == 'غير مسدد' ? 'text-danger' : 'text-success' }}">
                                {{ $loan->loan_status }}</td>
                            <td>{{ $loan->installment_start_date }}</td>
                            <td>{{ $loan->installment_amount }}</td>
                            <td>
                                <a href="{{ route('loan.show', $loan->id) }}" class="btn btn-success"><i
                                        class="las la-eye"></i></a>
                            </td>
                            @if ($loan->loan_status == 'تم التسديد')
                                <td><a href="" data-id="{{ $loan->id }}"
                                        class="btn btn-danger delete_loan"><i class="las la-times"></i></a></td>
                            @else
                                <td>
                                    <a href="" data-toggle="modal" data-target="#editLoan"
                                        data-loan_id="{{ $loan->id }}" data-loan_amount="{{ $loan->loan_amount }}"
                                        data-loan_currency="{{ $loan->currency }}"
                                        data-loan_name="{{ $loan->name }}"
                                        data-loan_status="{{ $loan->loan_status }}"
                                        data-installment_start_date="{{ $loan->installment_start_date }}"
                                        data-installment_amount="{{ $loan->installment_amount }}"
                                        class="btn btn-primary edit_loan"><i class="las la-edit"></i></a>
                                    <a href="" data-id="{{ $loan->id }}"
                                        class="btn btn-danger delete_loan"><i class="las la-times"></i></a>
                                </td>
                            @endif

                        </tr>

                    @empty
                        <tr>
                            <td colspan="11" class="text-center fs-3 text-danger">لا يوجد </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    </div>

    {!! $loans->links() !!}


    @extends('ajax.loan_js')

    {{-- modals --}}

    @extends('modals.addloan')
    @extends('modals.editloan')

    {{-- endmodal --}}
</body>

</html>
