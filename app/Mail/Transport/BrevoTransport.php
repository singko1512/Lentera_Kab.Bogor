<?php

namespace App\Mail\Transport;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $sender = $email->getFrom()[0] ?? null;
        $fromEmail = $sender ? $sender->getAddress() : config('mail.from.address');
        $fromName = $sender ? $sender->getName() : config('mail.from.name');

        $to = [];
        foreach ($email->getTo() as $recipient) {
            $to[] = [
                'email' => $recipient->getAddress(),
                'name' => $recipient->getName() ?: $recipient->getAddress(),
            ];
        }

        $payload = [
            'sender' => [
                'email' => $fromEmail,
                'name' => $fromName,
            ],
            'to' => $to,
            'subject' => $email->getSubject(),
            'htmlContent' => $email->getHtmlBody() ?: nl2br((string) $email->getTextBody()),
        ];

        if ($email->getTextBody()) {
            $payload['textContent'] = $email->getTextBody();
        }

        $response = Http::withoutVerifying()->withHeaders([
            'api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->timeout(15)->post('https://api.brevo.com/v3/smtp/email', $payload);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Brevo API Mail Error', [
                'status' => $response->status(),
                'body' => $errorBody,
            ]);
            throw new \RuntimeException('Gagal mengirim email via Brevo API (' . $response->status() . '): ' . $errorBody);
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}

