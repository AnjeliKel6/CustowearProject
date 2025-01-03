<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add(
            $request->id,
            $request->name,
            $request->quantity,
            $request->price
        )->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', Cart::instance('cart')->subtotal())->first();
            if (!$coupon) {
                // Debugging log untuk membantu identifikasi masalah
                if (!Coupon::where('code', $coupon_code)->exists()) {
                    return redirect()->back()->with('error', 'Coupon code does not exist.');
                }

                if (!Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())->exists()) {
                    return redirect()->back()->with('error', 'Coupon code has expired.');
                }

                if (!Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())
                    ->where('cart_value', '<=', Cart::instance('cart')->subtotal())->exists()) {
                    return redirect()->back()->with('error', 'Minimum cart value not met for this coupon.');
                }

                // Jika semua kondisi tidak terpenuhi
                return redirect()->back()->with('error', 'Invalid coupon code or conditions not met.');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success', 'Coupon has been applied!');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid coupon code!');
        }
    }

    public function calculateDiscount()
    {
        $discount = 0;
        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = (Cart::instance('cart')->subtotal() * Session::get('coupon')['value']) / 100;
            }

            $subtotalAfterDiscount = Cart::instance('cart')->subtotal() - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax')) / 100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
                'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
                'total' => number_format(floatval($totalAfterDiscount), 2, '.', '')
            ]);
        }
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success', 'Coupon has been removed!');
    }

    public function checkout()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $address = Address::where('user_id', Auth::user()->id)->where('isdefault', 1)->first();
        return view('checkout', compact('address'));
    }

    public function place_an_order(Request $request)
    {
        // Validasi metode pembayaran
        $request->validate([
            'mode' => 'required|in:transfer,cod',
        ]);

        // Ambil ID pengguna yang sedang login
        $user_id = Auth::id();

        // Cek alamat default pengguna
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        // Jika tidak ada alamat default, validasi dan simpan alamat baru
        if (!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:12',
                'zip' => 'required|numeric',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'address' => 'required|string|max:255',
                'locality' => 'required|string|max:100',
                'landmark' => 'required|string|max:255',
            ]);

            $address = new Address([
                'name' => $request->name,
                'phone' => $request->phone,
                'zip' => $request->zip,
                'state' => $request->state,
                'city' => $request->city,
                'address' => $request->address,
                'locality' => $request->locality,
                'landmark' => $request->landmark,
                'country' => 'Indonesia',
                'user_id' => $user_id,
                'isdefault' => true,
            ]);
            $address->save();
        }

        // Siapkan data checkout
        $this->setAmountforCheckout();

        // Buat order baru
        $order = new Order([
            'user_id' => $user_id,
            'subtotal' => Session::get('checkout')['subtotal'],
            'discount' => Session::get('checkout')['discount'],
            'tax' => Session::get('checkout')['tax'],
            'total' => Session::get('checkout')['total'],
            'name' => $address->name,
            'phone' => $address->phone,
            'locality' => $address->locality,
            'address' => $address->address,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
            'landmark' => $address->landmark,
            'zip' => $address->zip,
        ]);
        $order->save();

        // Simpan item yang dipesan
        foreach (Cart::instance('cart')->content() as $item) {
            $orderItem = new OrderItem([
                'product_id' => $item->id,
                'order_id' => $order->id,
                'price' => $item->price,
                'quantity' => $item->qty,
            ]);
            $orderItem->save();
        }

        // Proses transaksi berdasarkan metode pembayaran
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;
        $transaction->status = 'pending';

        if ($request->mode == 'transfer') {
            // Validasi transfer bank
            $request->validate([
                'bank' => 'required|string|max:100',
                'payment_proof' => 'required|file|mimes:jpeg,jpg,png,pdf|max:10240',
            ]);

            // Simpan bukti pembayaran
            $paymentProof = $request->file('payment_proof');
            $paymentProofPath = $paymentProof->store('payment_proofs', 'public');
            $transaction->bank = $request->bank;
            $transaction->payment_proof = $paymentProofPath;
        }
        elseif ($request->mode == "cod")
        {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }


        $transaction->save();

        // Bersihkan cart dan session terkait
        Cart::instance('cart')->destroy();
        Session::forget(['checkout', 'coupon', 'discounts']);
        Session::put('order_id', $order->id);

        // Redirect ke halaman konfirmasi
        return redirect()->route('cart.order.confirmation');
    }


    public function setAmountforCheckout()
    {
        if (!Cart::instance('cart')->content()->count() > 0) {
            Session::forget('checkout');
            return;
        }

        if (Session::has('coupon')) {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total'],
            ]);
        } else {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }
    }

    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}
