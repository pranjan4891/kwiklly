<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
        // Use ->stateless()->redirect() if you have session issues or building an API
    }

    // Handle callback
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

        } catch (\Exception $e) {
            // Log error if you want: \Log::error($e->getMessage());
            return redirect('/login')->with('error', 'Unable to login using Google. Please try again.');
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if ($user) {
            // If user exists but google_id missing, attach it
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name'      => $googleUser->getName() ?? $googleUser->getNickname() ?? 'User',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar'    => $googleUser->getAvatar(),
                // Set a random password (user can reset if needed)
                'password'  => bcrypt(Str::random(24)),
            ]);
        }

        // Log the user in
        Auth::login($user, true);
        $request->session()->regenerate();

        // Migrate session cart
        $sessionCart = session('cart', []);
        foreach ($sessionCart as $item) {
            CartItem::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                ],
                [
                    'quantity' => DB::raw("quantity + {$item['quantity']}"),
                    'price' => $item['price']
                ]
            );
        }
        session()->forget('cart');

        if (!empty($sessionCart)) {
            return redirect()->route('cart.view');
        }

        // Redirect to intended page
        return redirect()->intended('/');
    }
}
