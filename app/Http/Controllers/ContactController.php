<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // dd($request->all());
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $contact->save();

        return redirect()->back()->with('success', 'Message Sent');
    }

    public function index()
    {
        $contacts = Contact::orderby('created_at', 'desc')->paginate(20);
        return view('admin.contact', compact('contacts'));
    }
}
