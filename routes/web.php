<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PizzaItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

/********************  Admin Part ***********************/

// for direct open with admin index/login page 
Route::get('/admin', [AdminController::class, 'adminIndex'])->name('admin.login'); // dir. /  Controller   /  func.

Route::post('/admin/handleLogin', [AdminController::class, 'handleAdminLogin'])->name('admin.handleAdminLogin');

Route::get('/admin/index/{loginsuccess}', [AdminController::class, 'dashboardIndex'])->name('admin.index');  // dashboard / index

Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');  // dashboard

Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout'); // admin logout


/*************************   Profile Manage routes   ************************/
Route::get('/userManage', [UserController::class, 'userManageView'])->name('admin.userManageView');

Route::post('/userManage/addUser', [UserController::class, 'userManageAdd'])->name('admin.userManageAdd');

Route::put('/userManage/updateUser/{userid}', [UserController::class, 'userManageUpdate'])->name('admin.userManageUpdate');

Route::get('/userManage/destroyUser/{userid}', [UserController::class, 'userManageDestroy'])->name('admin.userManageDestroy');

Route::get('/viewProfile', [UserController::class, 'viewProfile'])->name('user.viewProfile');

Route::put('/manageProfile/{userid}', [UserController::class, 'manageProfile'])->name('user.manageProfile');


/********************  Users Part ***********************/
Route::post('/search', [UserController::class, 'userManageSearch'])->name('user.search');

Route::get('/about', [UserController::class, 'about'])->name('user.about');

Route::post('/handleUserSignup', [UserController::class, 'handleUserSignup'])->name('user.handleUserSignup');

Route::post('/handleUserLogin', [UserController::class, 'handleUserLogin'])->name('user.handleUserLogin');

Route::get('/logout', [UserController::class, 'userLogout'])->name('user.logout');


/*************************   contact manage routes   *************************/
Route::get('/contact', [UserController::class, 'contact'])->name('user.contact');

Route::post('/submitContact', [UserController::class, 'submitContact'])->name('user.submitContact');

Route::get('/admin/dashboard/contactManage', [AdminController::class, 'contactManage'])->name('admin.contactManage');

Route::post('/submitContactReply', [UserController::class, 'submitContactReply'])->name('admin.submitContactReply');


/*************************   categories routes   *************************/
Route::get('/admin/dashboard/manageCategory', [CategoryController::class, 'index'])->name('admin.manageCategory');

Route::post('/addCategory', [CategoryController::class, 'addCategory'])->name('category.addCategory');

Route::put('/updateImage/{catid}', [CategoryController::class, 'updateImage'])->name('category.updateImage');

Route::put('/updateCategory/{catid}', [CategoryController::class, 'updateCategory'])->name('category.updateCategory');

Route::get('/destroyCategory/{catid}', [CategoryController::class, 'destroyCategory'])->name('category.destroyCategory');

Route::get('/', [CategoryController::class, 'userIndex'])->name('user.index');



/*************************   pizza items routes   ************************/
Route::get('/admin/dashboard/managePizzaItems', [PizzaItemController::class, 'index'])->name('admin.managePizzaItems');

Route::post('/addPizzaItem', [PizzaItemController::class, 'addPizzaItem'])->name('pizzaitem.addPizzaItem');

Route::put('/updatePizzaImage/{pizzaid}', [PizzaItemController::class, 'updatePizzaImage'])->name('pizzaitem.updatePizzaImage');

Route::put('/updatePizzaItem/{pizzaid}', [PizzaItemController::class, 'updatePizzaItem'])->name('pizzaitem.updatePizzaItem');

Route::get('/destroyPizzaItem/{pizzaid}', [PizzaItemController::class, 'destroyPizzaItem'])->name('pizzaitem.destroyPizzaItem');

Route::get('/viewPizzaList/{catid}', [PizzaItemController::class, 'viewPizzaList'])->name('user.viewPizzaList');

Route::get('/viewPizza/{catid}/{pizzaid}', [PizzaItemController::class, 'viewPizza'])->name('user.viewPizza');



/*************************   cart routes   ************************/
Route::get('/pizzaCart', [CartController::class, 'showCart'])->name('user.showCart');

Route::post('/addToCart/{pizzaid}', [CartController::class, 'addToCart'])->name('cart.add');

Route::post('/addToCart2/{catid}', [CartController::class, 'addToCart2'])->name('cart.add2');

Route::post('/removeFromCart/{cartitemid}', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::post('/clearCart', [CartController::class, 'clearCart'])->name('cart.clear');

Route::post('/cart/updateQuantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

Route::get('/order-download/{orderid}', [CartController::class, 'orderDownload'])->name('order.download');




/*************************   order routes   ************************/

Route::post('/showCheckoutModal', [CartController::class, 'showCheckoutModal'])->name('user.showCheckoutModal'); // payment method

Route::post('/checkout', [CartController::class, 'checkout'])->name('user.checkout'); // checkout

Route::post('set-payment-method', [CartController::class, 'setPaymentMethod'])->name('set.payment.method');

Route::get('/viewOrders', [CartController::class, 'viewOrders'])->name('user.viewOrder');

Route::get('/admin/dashboard/manageOrders', [CartController::class, 'manageOrders'])->name('admin.manageOrders');

Route::get('/admin/dashboard/payments', [CartController::class, 'payments'])->name('admin.payments');

Route::put('/admin/dashboard/updateOrderStatus/{orderid}', [CartController::class, 'updateOrderStatus'])->name('admin.updateOrderStatus');

Route::put('/admin/dashboard/updateDeliveryBoy/{orderid}', [CartController::class, 'updateDeliveryBoy'])->name('admin.updateDeliveryBoy');

Route::post('/initiate-stripe-payment', [CartController::class, 'initiateStripePayment'])->name('stripe.initiate');

Route::get('/stripe/success', [CartController::class, 'stripeSuccess'])->name('stripe.success');

Route::get('/stripe/cancel', [CartController::class, 'stripeCancel'])->name('stripe.cancel');