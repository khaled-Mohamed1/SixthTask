<?php

namespace App\Imports;

use App\Models\Installment;
use App\Models\Loan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InstallmentImport implements ToCollection, WithHeadingRow
{

    private $loans;

    public function __construct()
    {
        $this->loans = Loan::select('id', 'name', 'currency')->get();
    }


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            $loan = $this->loans->Where('name', $row['name'])->where('currency', $row['currency'])->first();

            $data = Installment::where('loan_id', $loan->id)->get();

            $i = 0;
            $status = 'غير مسدد';
            $amount = $row['paid_amount'];
            $rest = 0;

            foreach ($data as $key => $item) {
                if ($item->loan_id == $loan->id) {
                    if ($item->installment_status == 'تم التسديد') {
                        ++$i;
                        $status = 'مسدد جزئي';
                        continue;
                    } elseif ($amount > $item->installment_amount && $rest == 0) {
                        $rest = ($amount + $item->paid_amount) - $item->installment_amount;
                        $item->update([
                            'paid_amount' => $item->installment_amount,
                            'installment_status' => 'تم التسديد',
                            'payment_date' => $row['payment_date']

                        ]);
                        ++$i;
                        continue;
                    } elseif ($rest > $item->installment_amount) {
                        $rest = ($rest + $item->paid_amount) - $item->installment_amount;
                        $item->update([
                            'paid_amount' => $item->installment_amount,
                            'installment_status' => 'تم التسديد',
                            'payment_date' => $row['payment_date']
                        ]);
                        ++$i;
                        continue;
                    } elseif ($rest < $item->installment_amount && $rest > 0) {
                        $item->update([
                            'paid_amount' => $rest,
                            'installment_status' => 'مسدد جزئي',
                            'payment_date' => $row['payment_date']
                        ]);
                        break;
                    } elseif ($rest == $item->installment_amount) {
                        $item->update([
                            'paid_amount' => $item->installment_amount,
                            'installment_status' => 'تم التسديد',
                            'payment_date' => $row['payment_date']
                        ]);
                        ++$i;
                        break;
                    } else {
                        if ($item->installment_amount == $row['paid_amount']) {
                            $item->update([
                                'paid_amount' => $amount,
                                'installment_status' => 'تم التسديد',
                                'payment_date' => $row['payment_date']

                            ]);
                            ++$i;

                            $status = 'مسدد جزئي';
                            if ($data->count() == $i) {
                                $status = 'مسدد كامل';
                            }

                            Loan::where('id', $loan->id)->update([
                                'loan_status' => $status,
                            ]);
                            break;
                        } else {
                            $item->update([
                                'paid_amount' => $amount + $item->paid_amount,
                                'installment_status' => 'غير مسدد',
                                'payment_date' => $row['payment_date']
                            ]);
                            if ($item->paid_amount == $item->installment_amount) {
                                $item->update([
                                    'installment_status' => 'تم التسديد'
                                ]);
                                ++$i;
                            }

                            $status = 'مسدد جزئي';

                            if ($data->count() == $i) {
                                $status = 'مسدد كامل';
                            }


                            Loan::where('id', $loan->id)->update([
                                'loan_status' => $status,
                            ]);
                            break;
                        }
                    }
                }
            }

            if ($data->count() == $i) {
                $status = 'تم التسديد';
                Loan::where('id', $loan->id)->update([
                    'loan_status' => $status,
                ]);
            } else {
                $status = 'مسدد جزئي';
                Loan::where('id', $loan->id)->update([
                    'loan_status' => $status,
                ]);
            }
        }
    }
}
