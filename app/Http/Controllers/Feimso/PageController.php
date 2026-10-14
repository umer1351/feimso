<?php

namespace App\Http\Controllers\Feimso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    // ── Auth ──────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('feimso.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstName'   => 'required|string|max:60',
            'lastName'    => 'required|string|max:60',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'feimso_role' => 'required|in:buyer,individual_seller,licensed_creator,business,advertising_creator',
            'agree_terms' => 'accepted',
        ]);

        $user = DB::table('users')->insertGetId([
            'id'           => Str::uuid(),
            'firstName'    => $request->firstName,
            'lastName'     => $request->lastName,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'feimso_role'  => $request->feimso_role,
            'referral_code'=> strtoupper(Str::random(8)),
            '_created'     => now(),
            '_updated'     => now(),
        ]);

        $userRecord = DB::table('users')->where('email', $request->email)->first();
        Auth::loginUsingId($userRecord->id ?? $user);

        return redirect()->route('feimso.verify-email');
    }

    public function showLogin()
    {
        return view('feimso.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('feimso.feed'));
        }

        return back()->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('feimso.login');
    }

    public function showForgot()
    {
        return view('feimso.auth.forgot');
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // In a real app: dispatch password reset email here.
        // For now just redirect to the "sent" screen.
        return redirect()->route('feimso.forgot.sent')
                         ->with('reset_email', $request->email);
    }

    public function forgotSent()
    {
        return view('feimso.auth.forgot-sent');
    }

    public function verifyEmail()
    {
        return view('feimso.auth.verify-email');
    }

    // ── Onboarding ────────────────────────────────────────────────

    public function onboardStep1()
    {
        return view('feimso.onboarding.step1');
    }

    public function onboardStep1Submit(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:60',
            'lastName'  => 'required|string|max:60',
            'phone'     => 'nullable|string|max:30',
            'address'   => 'nullable|string|max:255',
        ]);

        DB::table('users')->where('id', Auth::id())->update([
            'firstName'    => $request->firstName,
            'lastName'     => $request->lastName,
            'phoneNumber'  => $request->phone,
            'address'      => $request->address,
            'clothing_size'=> $request->clothing_size,
            'interests'    => json_encode($request->input('interests', [])),
            '_updated'     => now(),
        ]);

        return redirect()->route('feimso.onboarding.step2');
    }

    public function onboardStep2()
    {
        return view('feimso.onboarding.step2');
    }

    public function onboardStep2Submit(Request $request)
    {
        $request->validate([
            'username' => ['nullable','string','max:30','unique:users,username'],
            'bio'      => 'nullable|string|max:200',
        ]);

        $updates = [
            'username' => $request->username,
            'bio'      => $request->bio,
            '_updated' => now(),
        ];

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $updates['profilePictureURL'] = Storage::url($path);
        }

        DB::table('users')->where('id', Auth::id())->update($updates);

        return redirect()->route('feimso.feed');
    }

    public function checkUsername(Request $request)
    {
        $taken = DB::table('users')
            ->where('username', $request->username)
            ->where('id', '!=', Auth::id())
            ->exists();
        return response()->json(['available' => !$taken]);
    }

    // ── Feed ──────────────────────────────────────────────────────

    public function feed(Request $request)
    {
        $feedItems = [];

        try {
            // Listings from people the user follows (simplified: recent active listings)
            $listings = DB::table('listings')
                ->where('is_published', true)
                ->where('status', 'active')
                ->orderByDesc('_created')
                ->limit(10)
                ->get()
                ->map(fn($l) => array_merge(['type' => 'listing'], (array)$l))
                ->all();

            $feedItems = $listings;
        } catch (\Throwable) {
            $feedItems = [];
        }

        return view('feimso.feed.index', compact('feedItems'));
    }

    // ── Marketplace ───────────────────────────────────────────────

    public function marketplace(Request $request)
    {
        try {
            $query = DB::table('listings')
                ->where('is_published', true)
                ->where('status', 'active');

            if ($request->filled('listing_type')) {
                $query->where('listing_type', $request->listing_type);
            }
            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }
            if ($request->filled('sizes')) {
                $query->whereIn('size', $request->sizes);
            }

            $sort = $request->get('sort', 'relevant');
            match ($sort) {
                'newest'     => $query->orderByDesc('_created'),
                'price_asc'  => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                default      => $query->orderByDesc('_created'),
            };

            $listings = $query->paginate(18);
        } catch (\Throwable) {
            $listings = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 18);
        }

        return view('feimso.marketplace.index', compact('listings'));
    }

    // ── Listings ──────────────────────────────────────────────────

    public function showListing($id)
    {
        try {
            $row = DB::table('listings')->where('id', $id)->first();
            $listing = $row ? (array)$row : [];
        } catch (\Throwable) {
            $listing = [];
        }

        return view('feimso.listing.show', compact('listing'));
    }

    public function createListing()
    {
        return view('feimso.listing.create');
    }

    public function storeListing(Request $request)
    {
        $request->validate([
            'listing_type' => 'required|in:sale,rental,service,experience',
            'title'        => 'required|string|max:120',
            'description'  => 'nullable|string',
        ]);

        $isDraft = $request->has('draft');

        try {
            DB::table('listings')->insert([
                'id'                   => Str::uuid(),
                'seller_id'            => Auth::id(),
                'listing_type'         => $request->listing_type,
                'title'                => $request->title,
                'description'          => $request->description,
                'category'             => $request->category,
                'gender_target'        => $request->gender_target,
                'price'                => $request->price ?? $request->experience_price ?? 0,
                'currency'             => 'AED',
                'size'                 => $request->size,
                'condition'            => $request->condition,
                'brand'                => $request->brand,
                'colour'               => $request->colour,
                'price_per_day'        => $request->price_per_day,
                'deposit'              => $request->deposit,
                'pickup_type'          => $request->pickup_type,
                'duration_minutes'     => $request->duration_minutes,
                'max_participants'     => $request->max_participants,
                'available_from'       => $request->available_from,
                'available_to'         => $request->available_to,
                'location_type'        => $request->location_type,
                'scope_of_work'        => $request->scope_of_work,
                'delivery_option'      => $request->delivery_option,
                'cancellation_policy'  => $request->cancellation_policy,
                'authenticity_declared'=> $request->boolean('authenticity_declared'),
                'status'               => $isDraft ? 'draft' : 'active',
                'is_published'         => !$isDraft,
                '_created'             => now(),
                '_updated'             => now(),
            ]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['error' => 'Could not save listing: ' . $e->getMessage()]);
        }

        return redirect()->route('feimso.profile')
                         ->with('success', $isDraft ? 'Listing saved as draft.' : 'Listing published!');
    }

    // ── Profile ───────────────────────────────────────────────────

    public function profile(Request $request, $id = null)
    {
        $userId = $id ?? Auth::id();
        $profile = [];
        $stats   = ['listings' => 0, 'sold' => 0, 'rating' => '—'];

        try {
            $user = DB::table('users')->where('id', $userId)->first();
            $profile = $user ? (array)$user : [];

            $stats['listings'] = DB::table('listings')->where('seller_id', $userId)->where('is_published', true)->count();
            $stats['sold']     = DB::table('bookings')->where('seller_id', $userId)->where('status', 'completed')->count();
        } catch (\Throwable) {}

        return view('feimso.profile.show', compact('profile', 'stats'));
    }

    public function editProfile()
    {
        $profile = Auth::user() ? (array)DB::table('users')->where('id', Auth::id())->first() : [];
        return view('feimso.profile.edit', compact('profile'));
    }

    // ── Bookings ──────────────────────────────────────────────────

    public function storeBooking(Request $request)
    {
        $request->validate([
            'listing_id' => 'required',
        ]);

        try {
            $listing = DB::table('listings')->where('id', $request->listing_id)->firstOrFail();

            DB::table('bookings')->insert([
                'id'           => Str::uuid(),
                'listing_id'   => $request->listing_id,
                'buyer_id'     => Auth::id(),
                'seller_id'    => $listing->seller_id,
                'booking_type' => $listing->listing_type,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'subtotal'     => $listing->price,
                'currency'     => $listing->currency ?? 'AED',
                'payment_status' => 'pending',
                'status'       => 'pending',
                'buyer_notes'  => $request->buyer_notes,
                '_created'     => now(),
                '_updated'     => now(),
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Could not create booking.']);
        }

        return redirect()->route('feimso.profile')
                         ->with('success', 'Booking request sent to the seller.');
    }
}
