<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\User;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetUrl;
    public $token;
    public $validUntil;
    public $logoBase64;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $resetUrl, $token)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
        $this->token = $token;
        $this->validUntil = Carbon::now()->addMinutes(60)->format('d/m/Y H:i:s');
        
        // Generate base64 logo untuk embedding langsung
        $this->logoBase64 = $this->getLogoBase64();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kasbon Online - Reset Password - NIP: ' . $this->user->nip,
            from: config('mail.from.address', 'no-reply@kasbonsystem.com'),
            replyTo: config('mail.from.address', 'support@kasbonsystem.com'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
            with: [
                'user' => $this->user,
                'resetUrl' => $this->resetUrl,
                'token' => $this->token,
                'validUntil' => $this->validUntil,
                'logoBase64' => $this->logoBase64,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Coba beberapa lokasi logo yang mungkin
        $possiblePaths = [
            public_path('img/logo-qinka.png'),
            public_path('images/logo-qinka.png'),
            public_path('logo-qinka.png'),
            resource_path('images/logo-qinka.png'),
            base_path('public/img/logo-qinka.png'),
            'D:\Raka\PKL IMS\kasbon-online\public\img\logo-qinka.png',
        ];
        
        $logoPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $logoPath = $path;
                \Log::info('Logo found at: ' . $path);
                break;
            }
        }
        
        if ($logoPath) {
            return [
                Attachment::fromPath($logoPath)
                    ->as('logo.png')
                    ->withMime('image/png'),
            ];
        }
        
        \Log::warning('Logo not found in any expected location');
        return [];
    }

    /**
     * Get logo as base64 encoded string for direct embedding
     */
    private function getLogoBase64()
    {
        // Coba beberapa lokasi logo yang mungkin
        $possiblePaths = [
            public_path('img/logo-qinka.png'),
            public_path('images/logo-qinka.png'),
            public_path('logo-qinka.png'),
            resource_path('images/logo-qinka.png'),
            base_path('public/img/logo-qinka.png'),
            'D:\Raka\PKL IMS\kasbon-online\public\img\logo-qinka.png',
        ];
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                try {
                    $imageData = base64_encode(file_get_contents($path));
                    $mimeType = mime_content_type($path) ?: 'image/png';
                    return 'data:' . $mimeType . ';base64,' . $imageData;
                } catch (\Exception $e) {
                    \Log::error('Failed to encode logo to base64: ' . $e->getMessage());
                }
            }
        }
        
        \Log::warning('Logo not found for base64 encoding');
        return null;
    }
}