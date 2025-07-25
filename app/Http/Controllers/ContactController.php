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
        // Validar datos entrantes
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'email' => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Crear registro en BD (asegúrate que la tabla 'contacts' exista y modelo Contact esté configurado)
            $contact = Contact::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ]);

            return response()->json([
                'message' => 'Mensaje recibido correctamente',
                'contact' => $contact,
            ], 201);

        } catch (\Exception $e) {
            // Capturar errores inesperados
            return response()->json([
                'message' => 'Error al guardar el mensaje',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
