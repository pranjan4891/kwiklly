<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\ContactUsReply;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\MissionVision;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Stat;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{
    // Admin - list policies
    public function index() {
        $title = 'Product List';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $policies = Page::all();
        return view('admin.pages.index', compact('policies', 'title', 'admin'));
    }

    // Admin - create form
    public function create() {

        $title = 'Product List';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }

        return view('admin.pages.create', compact('title', 'admin'));
    }

    // Admin - store policy
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
        ]);

        Page::create($request->all());

        return redirect()->route('admin.policies.index')->with('success', 'Policy created successfully');
    }
    // Admin - edit form
    public function edit($id) {

        $title = 'Page Edit';
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page', 'title', 'admin'));
    }

    // Admin - update policy
    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $policy = Page::findOrFail($id);
        $policy->update($request->all());

        return redirect()->route('admin.policies.index')->with('success', 'Policy updated successfully');
    }

     // --- About Us ---
    public function aboutIndex()
    {
        $title = 'About Us';
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login')->with('error', 'Unauthorized');

        $about = AboutUs::first();
        return view('admin.pages.about', compact('about', 'title', 'admin'));
    }

    public function aboutUpdate(Request $request)
    {
        $about = AboutUs::first() ?? new AboutUs();
        $about->title = $request->title;
        $about->description = $request->description;
        $about->list_items = $request->list_items;

        // Image upload (same style as product_images)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'uploads/about';
            $image->move(public_path($imagePath), $imageName);
            $about->image = $imagePath . '/' . $imageName;
        }

        $about->save();
        return back()->with('success', 'About Us updated successfully');
    }


    // --- Mission & Vision ---
    public function missionIndex()
    {
        $title = 'Mission & Vision';
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login')->with('error', 'Unauthorized');

        $mission = MissionVision::first();
        return view('admin.pages.mission', compact('mission', 'title', 'admin'));
    }

    public function missionUpdate(Request $request)
    {
        $mission = MissionVision::first() ?? new MissionVision();
        $mission->title = $request->title;
        $mission->description = $request->description;

        // Image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'uploads/mission';
            $image->move(public_path($imagePath), $imageName);
            $mission->image = $imagePath . '/' . $imageName;
        }
        $mission->save();
        return back()->with('success', 'Mission & Vision updated');
    }

    // --- Stats ---
    public function statsIndex()
    {
        $title = 'Stats';
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login')->with('error', 'Unauthorized');

        $stats = Stat::all();
        return view('admin.pages.stats', compact('stats', 'title', 'admin'));
    }

    public function statsStore(Request $request)
    {
        Stat::create($request->all());
        return back()->with('success', 'Stat added');
    }

    public function statsUpdate(Request $request, $id)
    {
        $stat = Stat::findOrFail($id);
        $stat->update($request->all());
        return back()->with('success', 'Stat updated');
    }

    public function statsDestroy($id)
    {
        Stat::destroy($id);
        return back()->with('success', 'Stat deleted');
    }

    // --- Features ---
    public function featuresIndex()
    {
        $title = 'Features';
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login')->with('error', 'Unauthorized');

        $features = Feature::all();
        return view('admin.pages.features', compact('features', 'title', 'admin'));
    }

    public function featuresStore(Request $request)
    {
        Feature::create($request->all());
        return back()->with('success', 'Feature added');
    }

    public function featuresUpdate(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);
        $feature->update($request->all());
        return back()->with('success', 'Feature updated');
    }

    public function featuresDestroy($id)
    {
        Feature::destroy($id);
        return back()->with('success', 'Feature deleted');
    }

    // --- FAQs ---
    public function faqsIndex()
    {
        $title = 'FAQs';
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login')->with('error', 'Unauthorized');

        $faqs = Faq::all();
        return view('admin.pages.faqs', compact('faqs', 'title', 'admin'));
    }

    public function faqsStore(Request $request)
    {
        Faq::create($request->all());
        return back()->with('success', 'FAQ added');
    }

    public function faqsUpdate(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);
        $faq->update($request->all());
        return back()->with('success', 'FAQ updated');
    }

    public function faqsDestroy($id)
    {
        Faq::destroy($id);
        return back()->with('success', 'FAQ deleted');
    }

    // ---Contact Us---
    public function contactIndex()
    {
        $title = 'Admin | Contact Us';
        $admin = Auth::guard('admin')->user();
        if (!$admin) return redirect()->route('admin.login')->with('error', 'Unauthorized');
        $contacts = ContactUs::all();
        return view('admin.pages.contact', compact('title', 'admin', 'contacts'));
    }


    public function replyMessage(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $contact = ContactUs::findOrFail($id);

        ContactUsReply::create([
            'contact_us_id' => $contact->id,
            'reply_message' => $request->reply,
        ]);

        // If you also want to send email:
        // Mail::to($contact->email)->send(new ContactReplyMail($contact, $request->reply));

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

}
