<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\CartItem;
use App\Services\LocationServiceability;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacebookController extends Controller
{
    public function redirectToFacebook(Request $request)
    {
        $lat = $request->query('latitude');
        $lng = $request->query('longitude');
        $checker = app(LocationServiceability::class);
        if (! $lat || ! $lng || ! $checker->isServiceable((float) $lat, (float) $lng)) {
            return redirect()
                ->route('loginbyphone')
                ->with('error', 'Please select a serviceable delivery location in the header, then try Facebook login again.');
        }

        session([
            'oauth_login_location' => [
                'lat' => (float) $lat,
                'lng' => (float) $lng,
            ],
        ]);

        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback(Request $request)
    {
        $loc = session('oauth_login_location');
        $checker = app(LocationServiceability::class);
        if (! $loc || ! $checker->isServiceable((float) ($loc['lat'] ?? 0), (float) ($loc['lng'] ?? 0))) {
            return redirect()
                ->route('loginbyphone')
                ->with('error', 'Delivery is not available at your selected location. Select a serviceable area and try again.');
        }

        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Facebook login failed. Please try again.');
        }

        session()->forget('oauth_login_location');

        $user = User::where('facebook_id', $facebookUser->getId())
                    ->orWhere('email', $facebookUser->getEmail())
                    ->first();

        if ($user) {
            if (!$user->facebook_id) {
                $user->update([
                    'facebook_id' => $facebookUser->getId(),
                    'avatar' => $facebookUser->getAvatar(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $facebookUser->getName() ?? 'Facebook User',
                'email' => $facebookUser->getEmail(),
                'facebook_id' => $facebookUser->getId(),
                'avatar' => $facebookUser->getAvatar(),
                'password' => bcrypt(Str::random(24)),
            ]);
        }

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

        // Redirect to intended page or dashboard
        return redirect()->intended('/');
    }

}
