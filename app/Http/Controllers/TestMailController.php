<?php

namespace App\Http\Controllers;

use App\Mail\NewPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function sendTestEmail()
    {
        try {
            $email = 'test@example.com';
            $newPassword = 'TestPassword123!';
            
            Mail::to($email)->send(new NewPasswordMail($newPassword));
            
            return response()->json(['message' => 'Test email sent successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send test email',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
