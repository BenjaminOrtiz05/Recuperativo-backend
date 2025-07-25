<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name'    => 'required|string|min:2',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors(),
            ], 422);
        }

        $contact = Contact::create($validated->validated());

        return response()->json([
            'message' => 'Mensaje enviado correctamente.',
            'data' => $contact,
        ]);
    }
}
