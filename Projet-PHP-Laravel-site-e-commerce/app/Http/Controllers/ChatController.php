<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    /**
     * Forward a message to the Flask chatbot API
     */
    public function ask(Request $request)
    {
        try {
            $response = Http::post('http://localhost:5000/ask', [
                'message' => $request->input('message')
            ]);
            
            return $response->json();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to communicate with chatbot service'
            ], 500);
        }
    }
} 