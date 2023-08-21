<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListingController extends Controller
{
     // get and show all listings
     public function index() {
        // dd(request('tag'));
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->get()
        ]);
    }

    // show single listing
    public function show(Listing $listing) {
        return view('listings.show', [
            // the 'listing' will come from the 'Listing Model' then 'find id'
            'listing' =>  $listing
        ]);
    }


    // Show Create Form
    public function create() {
        return view('listings.create');
    }
}


