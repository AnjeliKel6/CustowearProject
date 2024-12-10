<?php

use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AuthSupplier;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\WishlistController;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop',[ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}',[ShopController::class, 'product_details'])->name('shop.product.details');

Route::get('/cart',[CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add',[CartController::class, 'add_to_cart'])->name('cart.add');
Route::put('cart/increase-quantity/{rowId}',[CartController::class, 'increase_cart_quantity'])->name('cart.qty.increase');
Route::put('cart/decrease-quantity/{rowId}',[CartController::class, 'decrease_cart_quantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}',[CartController::class, 'remove_item'])->name('cart.item.remove');
Route::delete('/cart/clear',[CartController::class, 'empty_cart'])->name('cart.empty');

Route::post('/cart/apply-coupon',[CartController::class, 'apply_coupon_code'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon',[CartController::class, 'remove_coupon_code'])->name('cart.coupon.remove');

Route::post('/wishlist/add',[WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
Route::get('/wishlist',[WishlistController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/item/remove/{rowId}',[WishlistController::class, 'remove_item'])->name('wishlist.item.remove');
Route::delete('/wishlist/clear',[WishlistController::class, 'empty_wishlist'])->name('wishlist.item.clear');
Route::post('/wishlist/move-to-cart/{rowId}',[WishlistController::class, 'move_to_cart'])->name('wishlist.move.to.cart');

Route::get('/checkout',[CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/place-an-order',[CartController::class, 'place_an_order'])->name('cart.place.an.order');
Route::get('/order-confirmation',[CartController::class, 'order_confirmation'])->name('cart.order.confirmation');

Route::get('/contact-us',[HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact/store',[HomeController::class, 'contact_store'])->name('home.contact.store');

Route::get('/search',[HomeController::class, 'search'])->name('home.search');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index')->middleware(['auth', 'checkType:USR']);
    Route::get('/account-orders',[UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-order/{order_id}/details',[UserController::class, 'order_details'])->name('user.order.details');
    Route::put('/account-order/cancel-order',[UserController::class, 'order_cancel'])->name('user.order.cancel');


});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware(['auth', 'checkType:ADM']);
    Route::get('/admin/brands',[AdminController::class,'brands'])->name('admin.brands');
    Route::get('/admin/brand/add',[AdminController::class,'brand_add'])->name('admin.brand.add');
    Route::post('/admin/brand/store',[AdminController::class,'brand_store'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}',[AdminController::class, 'brand_edit'])->name('admin.brand.edit');
    Route::put('admin/brand/update', [AdminController::class, 'brand_update'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete',[AdminController::class, 'brand_delete'])->name('admin.brand.delete');

    Route::get('/admin/categories',[AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/category/add',[AdminController::class, 'category_add'])->name('admin.category.add');
    Route::post('/admin/category/store',[AdminController::class, 'category_store'])->name('admin.category.store');
    Route::get('/admin/category/{id}/edit',[AdminController::class, 'category_edit'])->name('admin.category.edit');
    Route::put('admin/category/update', [AdminController::class, 'category_update'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete',[AdminController::class, 'category_delete'])->name('admin.category.delete');

    Route::get('/admin/products',[AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/product/add',[AdminController::class, 'product_add'])->name('admin.product.add');
    Route::post('admin/product/store',[AdminController::class, 'product_store'])->name('admin.product.store');
    Route::get('/admin/product/{id}/edit',[AdminController::class, 'product_edit'])->name('admin.product.edit');
    Route::put('admin/product/update', [AdminController::class, 'product_update'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete',[AdminController::class, 'product_delete'])->name('admin.product.delete');

    Route::get('/admin/coupons',[AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add',[AdminController::class, 'coupon_add'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store',[AdminController::class, 'coupon_store'])->name('admin.coupon.store');
    Route::get('/admin/coupon/{id}/edit',[AdminController::class, 'coupon_edit'])->name('admin.coupon.edit');
    Route::put('admin/coupon/update', [AdminController::class, 'coupon_update'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete',[AdminController::class, 'coupon_delete'])->name('admin.coupon.delete');

    Route::get('/admin/orders',[AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details',[AdminController::class, 'order_details'])->name('admin.order.details');
    Route::put('/admin/order/update-status',[AdminController::class, 'update_order_status'])->name('admin.order.status.update');

    Route::get('/admin/slides',[AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slide/add',[AdminController::class, 'slide_add'])->name('admin.slide.add');
    Route::post('/admin/slide/store',[AdminController::class, 'slide_store'])->name('admin.slide.store');
    Route::get('/admin/slide/{id}/edit',[AdminController::class, 'slide_edit'])->name('admin.slide.edit');
    Route::put('/admin/slide/update',[AdminController::class, 'slide_update'])->name('admin.slide.update');
    Route::delete('/admin/slide/{id}/delete',[AdminController::class, 'slide_delete'])->name('admin.slide.delete');

    Route::get('/admin/contact',[AdminController::class, 'contacts'])->name('admin.contacts');
    Route::delete('/admin/contact/{id}/delete',[AdminController::class, 'contact_delete'])->name('admin.contact.delete');

    Route::get('/admin/search',[AdminController::class, 'search'])->name('admin.search');

    Route::get('/admin/user',[AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/user/add',[AdminController::class, 'users_add'])->name('admin.users.add');
    Route::post('/admin/user/store',[AdminController::class, 'users_store'])->name('admin.users.store');
    Route::get('/admin/user/{id}/edit',[AdminController::class, 'users_edit'])->name('admin.users.edit');
    Route::put('admin/user/update', [AdminController::class, 'users_update'])->name('admin.users.update');
    Route::delete('/admin/user/{id}/delete',[AdminController::class, 'users_delete'])->name('admin.users.delete');

});

Route::middleware(['auth',AuthSupplier::class])->group(function(){
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/materials',[SupplierController::class, 'materials'])->name('supplier.materials');
    Route::get('/supplier/material/add',[SupplierController::class,'materials_add'])->name('supplier.material.add');
    Route::post('/supplier/material/store',[SupplierController::class,'materials_store'])->name('supplier.material.store');
    Route::get('/supplier/material/{id}/edit',[SupplierController::class, 'materials_edit'])->name('supplier.material.edit');
    Route::put('supplier/material/update', [SupplierController::class, 'materials_update'])->name('supplier.material.update');
    Route::delete('/supplier/material/{id}/delete',[SupplierController::class, 'materials_delete'])->name('supplier.material.delete');

    // Route::get('/supplier/suppliers',[SupplierController::class,  'suppliers'])->name('supplier.suppliers');
    // Route::get('/supplier/suppliers/add',[SupplierController::class,  'suppliers_add'])->name('supplier.suppliers.add');
    // Route::post('/supplier/suppliers/store',[SupplierController::class,  'suppliers_store'])->name('supplier.suppliers.store');
    // Route::get('/supplier/suppliers/{id}/edit',[SupplierController::class,  'suppliers_edit'])->name('supplier.suppliers.edit');
    // Route::put('/supplier/suppliers/update', [SupplierController::class,  'suppliers_update'])->name('supplier.suppliers.update');
    // Route::delete('/supplier/suppliers/{id}/delete',[SupplierController::class, 'suppliers_delete'])->name('supplier.suppliers.delete');
});

// Route::get('/tracking/{orderId}', [TrackingController::class, 'show']);



