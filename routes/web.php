<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\CustomerController;

Route::get('/', [VehicleController::class, 'home'])->name('home.page');
Route::get('/home', [VehicleController::class, 'home'])->name('home');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('/bcrypt', function () {
    return bcrypt('123456');
});

Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/bookings', [ContractController::class, 'book'])
    ->middleware('auth')
    ->name('bookings.store');


Route::middleware(['authAdmin'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/invoices/{id}/confirm-payment', [InvoiceController::class, 'confirmPayment'])->name('invoices.confirmPayment');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/admin/vehicles', [VehicleController::class, 'adminIndex'])->name('admin.vehicles.index');
    Route::get('/admin/vehicles/create', [VehicleController::class, 'create'])->name('admin.vehicles.create');
    Route::post('/admin/vehicles', [VehicleController::class, 'store'])->name('admin.vehicles.store');
    Route::get('/admin/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('admin.vehicles.edit');
    Route::put('/admin/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('admin.vehicles.update');
    Route::delete('/admin/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('admin.vehicles.destroy');

    Route::get('/payment_methods', [PaymentMethodController::class, 'index'])->name('payment_methods.index');
    Route::get('/payment_methods/create', [PaymentMethodController::class, 'create'])->name('payment_methods.create');
    Route::post('/payment_methods', [PaymentMethodController::class, 'store'])->name('payment_methods.store');
    Route::get('/payment_methods/{payment_method}/edit', [PaymentMethodController::class, 'edit'])->name('payment_methods.edit');
    Route::put('/payment_methods/{payment_method}', [PaymentMethodController::class, 'update'])->name('payment_methods.update');
    Route::delete('/payment_methods/{payment_method}', [PaymentMethodController::class, 'destroy'])->name('payment_methods.destroy');

    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');

    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}/edit', [ContractController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    // Tìm trong nhóm middleware authAdmin và thêm dòng này vào:
    // Trong routes/web.php, Duyệt sửa dòng này:

    Route::get('/invoices/create-step-1', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices/create-step-2', [InvoiceController::class, 'createStep2'])->name('invoices.createStep2');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('invoice-details', [InvoiceDetailController::class, 'index'])->name('invoiceDetails.index');
    Route::get('invoice-details/create', [InvoiceDetailController::class, 'create'])->name('invoiceDetails.create');
    Route::post('invoice-details', [InvoiceDetailController::class, 'store'])->name('invoiceDetails.store');
    Route::get('invoice-details/{service_id}/{invoice_id}/edit', [InvoiceDetailController::class, 'edit'])->name('invoiceDetails.edit');
    Route::put('invoice-details/{service_id}/{invoice_id}', [InvoiceDetailController::class, 'update'])->name('invoiceDetails.update');
    Route::delete('invoice-details/{service_id}/{invoice_id}', [InvoiceDetailController::class, 'destroy'])->name('invoiceDetails.destroy');

    Route::get('serviceDetails', [ServiceDetailController::class, 'index'])->name('serviceDetails.index');
    Route::get('serviceDetails/create', [ServiceDetailController::class, 'create'])->name('serviceDetails.create');
    Route::post('serviceDetails', [ServiceDetailController::class, 'store'])->name('serviceDetails.store');
    Route::get('serviceDetails/{vehicle_id}/{service_id}/edit', [ServiceDetailController::class, 'edit'])->name('serviceDetails.edit');
    Route::put('serviceDetails/{vehicle_id}/{service_id}', [ServiceDetailController::class, 'update'])->name('serviceDetails.update');
    Route::delete('serviceDetails/{vehicle_id}/{service_id}', [ServiceDetailController::class, 'destroy'])->name('serviceDetails.destroy');


});

Route::middleware(['auth'])->group(function () {
    Route::get('/customer/home', [CustomerController::class, 'dashboard'])->name('customer.home');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/customer/contract', [CustomerController::class, 'viewContract'])->name('customer.contract');
    Route::get('/customer/invoices', [CustomerController::class, 'viewInvoices'])->name('customer.invoices');
    Route::put('/customer/password/update', [CustomerController::class, 'updatePassword'])->name('customer.password.update');
    Route::get('/customer/statistics', [CustomerController::class, 'statistics'])->name('customer.statistics');
});
