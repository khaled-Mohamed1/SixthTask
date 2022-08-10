<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loans = Loan::latest()->paginate(10);
        return view("loans.index", compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addLoan(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'installment_start_date' => 'required|date|after:' . $request->date,
                'loan_amount' => 'required',
                'currency' => 'required',
                'loan_status' => 'required',
                'installment_amount' => 'required',
                'installment_amount' => 'required|numeric|max:' . $request->loan_amount,

            ],
            [
                'name.required' => 'يجب ادخال اسم الموظف',
                'loan_amount.required' => 'يجب ادخال قيمة العرض',
                'currency.required' => 'يجب ادخال نوع العملة',
                'loan_status.required' => 'يجب ادخال حالة القرض',
                'installment_start_date.required' => 'يجب ادخال تاريخ بداية القسط',
                'installment_amount.required' => 'يجب ادخال قيمة القسط',
                'installment_amount.max' => 'يجب ان يكون قيمة القسط اصغر من مبلغ القرض',

            ]
        );

        $loan_id = Loan::create([
            'name' => $request->name,
            'loan_amount' => $request->loan_amount,
            'currency' => $request->currency,
            'loan_status' => $request->loan_status,
            'date' => $request->date,
            'installment_start_date' => $request->installment_start_date,
            'installment_amount' => $request->installment_amount,
        ]);

        $loan_id = $loan_id->id;
        $mydate = strtotime($request->installment_start_date);
        $newformat = date('Y-m-d', $mydate);
        $loan_rest = $request->installment_amount;

        $float = $request->loan_amount / $request->installment_amount;
        for ($i = 1; $i <= $request->test_number_of_installment; $i++) {

            if ($i == $request->test_number_of_installment && is_float($float)) {
                $loan_rest = $request->loan_rest;
            }

            Installment::create([
                'loan_id' => $loan_id,
                'installment_amount' => $loan_rest,
                'due_date' => Carbon::createFromFormat('Y-m-d', $newformat)->addMonth($i),
                'installment_status' => 'غير مسدد',
            ]);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::findorFail($id);
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function editLoan(Request $request, Loan $loan)
    {
        $request->validate(
            [
                'edit_name' => 'required',
                'edit_loan_amount' => 'required',
                'edit_currency' => 'required',
                'edit_loan_status' => 'required',
                'edit_installment_start_date' => 'required',
                'edit_installment_amount' => 'required',

            ],
            [
                'edit_name.required' => 'يجب ادخال اسم الموظف',
                'edit_loan_amount.required' => 'يجب ادخال قيمة العرض',
                'edit_currency.required' => 'يجب ادخال نوع العملة',
                'edit_loan_status.required' => 'يجب ادخال حالة القرض',
                'edit_installment_start_date.required' => 'يجب ادخال تاريخ بداية القسط',
                'edit_installment_amount.required' => 'يجب ادخال قيمة القسط',
            ]
        );

        $loan = Loan::where('id', $request->edit_id)->get();
        $old_loan_amount = $request->edit_loan_amount;
        $old_installment_amount = $request->edit_installment_amount;
        foreach ($loan as $loans) {
            $loans_loan_amount = $loans->loan_amount;
            $loans_installment_amount = $loans->installment_amount;
        }

        if ($loans_loan_amount == $old_loan_amount && $loans_installment_amount == $old_installment_amount) {
            Loan::where('id', $request->edit_id)->update([
                'name' => $request->edit_name,
                'loan_amount' => $request->edit_loan_amount,
                'currency' => $request->edit_currency,
                'loan_status' => $request->edit_loan_status,
                'installment_start_date' => $request->edit_installment_start_date,
                'installment_amount' => $request->edit_installment_amount,
            ]);
        } else {

            Loan::where('id', $request->edit_id)->update([
                'name' => $request->edit_name,
                'loan_amount' => $request->edit_loan_amount,
                'currency' => $request->edit_currency,
                'loan_status' => $request->edit_loan_status,
                'installment_start_date' => $request->edit_installment_start_date,
                'installment_amount' => $request->edit_installment_amount,

            ]);


            Installment::where('loan_id', $request->edit_id)->where('paid_amount', null)->delete();
            $payment = Installment::where('loan_id', $request->edit_id)->whereNotNull('paid_amount')->sum('installment_amount');

            $edit_number_of_installment = ($request->edit_loan_amount - $payment) / $request->edit_installment_amount;
            $edit_test_number_of_installment = ceil($edit_number_of_installment);
            $new_edit_loan_rest = ($request->edit_loan_amount - $payment) - (($edit_test_number_of_installment - 1) * $request->edit_installment_amount);

            $mydate = strtotime($request->edit_installment_start_date);
            $newformat = date('Y-m-d', $mydate);
            $edit_loan_rest = $request->edit_installment_amount;


            $float =  ($request->edit_loan_amount - $payment) / $request->edit_installment_amount;
            for ($i = 1; $i <= $edit_test_number_of_installment; $i++) {

                if ($i == $edit_test_number_of_installment && is_float($float)) {
                    $edit_loan_rest = $new_edit_loan_rest;
                }

                Installment::create([
                    'loan_id' => $request->edit_id,
                    'installment_amount' => $edit_loan_rest,
                    'due_date' => Carbon::createFromFormat('Y-m-d', $newformat)->addMonth($i),
                    'installment_status' => 'غير مسدد',
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Loan  $loan
     * @return \Illuminate\Http\Response
     */
    public function deleteLoan(Request $request)
    {
        Loan::find($request->loan_id)->delete();
        return response()->json([
            'status' => 'success',
        ]);
    }
}
