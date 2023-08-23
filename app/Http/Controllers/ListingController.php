<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
     // get and show all listings
     public function index() {
        // dd(request('tag'));
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6)
        ]);
    }
    

    // show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            // the 'listing' will come from the 'Listing Model' then 'find id'
            'listing' => $listing
        ]);
    }


    // Show Create Form
    public function create() {
        return view('listings.create');
    }


    // Store Listing Data
    public function store(Request $request) {
        // dd($request->file('logo')->store());
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logos', 'public')->store();
        }

        // Tie Listings to User
        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        // Session::flash('message', 'Listing Created');
        
        return redirect('/')->with('message', 'Listing Created successfully!');
    }

    // Show Edit Form
    public function edit(Listing $listing) {
        return view('listing.edit', ['listing' => $listing]);
    }

      // Update Listing Data
      public function update(Request $request, Listing $listing) {

        // dd($request->file('logo')->store());

        // Make sure logged in user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);
        
        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logos', 'public')->store();
        }

        $listing->update($formFields);

        // Session::flash('message', 'Listing Created');
        
        return back()->with('message', 'Listing Updated successfully!');
    }


    // Delete Listing
    public function destroy(Listing $listing) {
        
        // Make sure logged in user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');
    }


    // Manage Listings
    public function manage() {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }


}



