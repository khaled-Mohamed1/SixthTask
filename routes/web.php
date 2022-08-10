<?php

use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(LoanController::class)->group(function () {
    Route::get('loans', 'index')->name('loans');
    Route::get('loan/show/{id}', 'show')->name('loan.show');
    Route::post('loans/add-loan', 'addLoan')->name('add.loan');
    Route::post('loans/edit-loan', 'editLoan')->name('edit.loan');
    Route::post('loans/delete-loan', 'deleteLoan')->name('delete.loan');

});

Route::controller(InstallmentController::class)->group(function () {
    Route::post('loans/delete-installment', 'deleteInstallment')->name('delete.installment');
    Route::post('loans/payment-installment', 'paymentInstallment')->name('payment.installment');
    Route::post('loans/edit_payment-installment', 'editPaymentInstallment')->name('editpayment.installment');
});

