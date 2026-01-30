<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Smtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class SmtpController extends Controller
{
    public function index()
    {
        try {
            $smtp = Smtp::first();

            if (!$smtp) {
                $smtp = Smtp::create([
                    'mail_mailer' => 'smtp',
                    'mail_host' => '',
                    'mail_port' => '587',
                    'mail_username' => '',
                    'mail_password' => '',
                    'mail_encryption' => 'tls',
                    'mail_from_address' => '',
                    'mail_from_name' => '',
                ]);
            }

            return view('backend.admin.setting.smtp', compact('smtp'));
        } catch (\Exception $e) {
            Log::error('SMTP Index Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching SMTP settings.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer'      => 'required|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host'        => 'required|string|max:255',
            'mail_port'        => 'required|integer|in:25,465,587,2525',
            'mail_username'    => 'nullable|string|max:255',
            'mail_password'    => 'nullable|string|max:255',
            'mail_encryption'  => 'nullable|in:tls,ssl,null',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name'   => 'nullable|string|max:255',
        ]);


        if (!empty($validated['mail_password'])) {
            $validated['mail_password'] = Crypt::encryptString($validated['mail_password']);
        }


        Smtp::updateOrCreate(
            $validated
        );

        return redirect()
            ->back()
            ->with('success', 'SMTP settings saved successfully.');
    }
}
