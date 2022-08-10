<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paymentInstallment(Request $request)
    {

        $installment = Installment::select("paid_amount")->where("id", "LIKE", "%{$request->installment_id}%")
            ->get();
        $amount = $installment[0]->paid_amount;
        if ($amount == null) {
            $amount = 0;
        }

        // $installment_paid_amount = $installment->paid_amount;
        $request->validate(
            [
                'paid_amount' => 'required',
                'paid_amount' => 'required|numeric|max:' . $request->installment_amount - $amount,

            ],
            [
                'paid_amount.required' => 'يجب ادخال مبلغ الدفع',
                'paid_amount.max' => 'يجب ان يكون المبلغ المدفوع اقل من قيمة القسط الذي تم دفعه ويساوي ' . $request->installment_amount - $amount,

            ]
        );

        $status = 'غير مسدد';
        if ($request->paid_amount + $amount == $request->installment_amount) {
            $status = 'تم التسديد';
        }

        Installment::where('id', $request->installment_id)->update([
            'paid_amount' => $request->paid_amount + $amount,
            'payment_date' => now()->toDateTimeString(),
            'installment_status' => $status,
        ]);

        // $status = Installment::where('loan_id', $request->loan_id)->withCount('installment_status')->get();
        $loan = Installment::where('loan_id', $request->loan_id)->get();

        $i = 0;
        $status = 'غير مسدد';
        foreach ($loan as $installment) {
            if ($installment->installment_status == 'تم التسديد') {
                ++$i;
                $status = 'مسدد جزئي';
            }
        }

        if ($loan->count() == $i) {
            $status = 'مسدد كامل';
        }

        Loan::where('id', $request->loan_id)->update([
            'loan_status' => $status,
        ]);


        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function show(Installment $installment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function edit(Installment $installment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function editPaymentInstallment(Request $request)
    {

        $last = Installment::where('id', $request->edit_id)->get();
        foreach ($last as $item) {
            $old_installment_amount = $item->installment_amount;
        }

        // dd($old_installment_amount);

        if ($request->edit_installment_amount > $old_installment_amount) {
            $request->validate(
                [
                    'edit_installment_amount' => 'required',
                    'edit_installment_amount' => 'required|numeric|max:' . $old_installment_amount,

                ],
                [
                    'edit_installment_amount.required' => 'يجب ادخال قيمة القسط',
                    'edit_installment_amount.max' => 'يجب ان يكون قيمة القسط اصغر من قيمة القسط الأصلية ',

                ]
            );
            
        } elseif ($request->edit_installment_amount < $old_installment_amount) {
            $data = Installment::where('id', $request->edit_id)->get();
            foreach ($data as $item) {
                $loan_id = $item->loan_id;
                $installment_amount = $item->installment_amount;
            }

            $rest_installment = $installment_amount - $request->edit_installment_amount;
            Installment::where('id', $request->edit_id)->update([
                'installment_amount' => $request->edit_installment_amount,
            ]);


            $last = Installment::find($request->edit_id)->get()->sortBy('due_date')->last();
            $due_date = $last->due_date;

            $mydate = strtotime($due_date);
            $newformat = date('Y-m-d', $mydate);

            Installment::create([
                'loan_id' => $loan_id,
                'installment_amount' => $rest_installment,
                'due_date' => Carbon::createFromFormat('Y-m-d', $newformat)->addMonth(),
                'installment_status' => 'غير مسدد',
            ]);
        } else {
            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function deleteInstallment(Request $request)
    {

        $data = Installment::where('id', $request->installment_id)->get();
        foreach ($data as $item) {
            $loan_id = $item->loan_id;
            $installment_amount = $item->installment_amount;
            $installment_status = $item->installment_status;
        }

        $last = Installment::find($request->installment_id)->get()->sortBy('due_date')->last();
        $due_date = $last->due_date;

        $mydate = strtotime($due_date);
        $newformat = date('Y-m-d', $mydate);

        Installment::where('id', $request->installment_id)->update([
            'loan_id' => $loan_id,
            'installment_amount' => $installment_amount,
            'due_date' => Carbon::createFromFormat('Y-m-d', $newformat)->addMonth(),
            'installment_status' => $installment_status,
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }
}
