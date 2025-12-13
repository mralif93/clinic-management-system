# WhatsApp Integration with Laravel - Complete Guide

## Table of Contents
1. [Overview](#overview)
2. [Integration Options](#integration-options)
3. [Twilio WhatsApp API Setup](#twilio-whatsapp-api-setup)
4. [Laravel Implementation](#laravel-implementation)
5. [Sending Messages](#sending-messages)
6. [Receiving Messages](#receiving-messages)
7. [Webhook Configuration](#webhook-configuration)
8. [Advanced Features](#advanced-features)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)

---

## Overview

WhatsApp integration allows your clinic management system to:
- Send appointment reminders and notifications to patients
- Receive customer support inquiries via WhatsApp
- Automate responses to common questions
- Send appointment confirmations and updates
- Provide 24/7 customer support capabilities

This documentation covers integrating WhatsApp Business API with Laravel using Twilio as the service provider.

---

## Integration Options

### 1. Twilio WhatsApp API (Recommended)
**Pros:**
- Easy to set up and use
- Excellent Laravel/PHP SDK support
- Sandbox environment for testing
- Comprehensive documentation
- Reliable webhook handling
- Supports media messages

**Cons:**
- Requires Twilio account (pricing per message)
- Additional service layer

**Best for:** Most applications, especially when you need quick setup

### 2. WhatsApp Business API (Direct)
**Pros:**
- Direct integration with Meta/Facebook
- No intermediary service
- Potentially lower costs at scale

**Cons:**
- Complex setup process
- Requires business verification
- Longer approval times
- More complex webhook handling

**Best for:** Large enterprises with dedicated technical resources

### 3. Other Third-Party Services
- **360dialog**: WhatsApp Business API provider
- **ChatAPI**: WhatsApp API service
- **Wati**: WhatsApp Business API platform

**Best for:** Specific regional requirements or specialized needs

---

## Twilio WhatsApp API Setup

### Step 1: Create Twilio Account

1. Sign up at [https://www.twilio.com/try-twilio](https://www.twilio.com/try-twilio)
2. Verify your email and phone number
3. Navigate to the Twilio Console Dashboard

### Step 2: Activate WhatsApp Sandbox

1. Go to **Messaging** → **Try it out** → **Send a WhatsApp message**
2. You'll see a sandbox number (usually `+14155238886`)
3. Send a message to the sandbox number with the code: `join <your-sandbox-code>`
4. You'll receive a confirmation message

**Note:** The sandbox is for testing only. For production, you'll need to register a WhatsApp Business number.

### Step 3: Get Your Credentials

From the Twilio Console:
- **Account SID**: Found on the dashboard
- **Auth Token**: Found on the dashboard (click to reveal)
- **WhatsApp Number**: Your sandbox number (e.g., `+14155238886`)

---

## Laravel Implementation

### Step 1: Install Twilio SDK

```bash
composer require twilio/sdk
```

### Step 2: Configure Environment Variables

Add to your `.env` file:

```env
TWILIO_ACCOUNT_SID=your_account_sid_here
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_NUMBER=+14155238886
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

### Step 3: Create Service Configuration

Create or update `config/services.php`:

```php
<?php

return [
    // ... other services

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_number' => env('TWILIO_WHATSAPP_NUMBER'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM', 'whatsapp:' . env('TWILIO_WHATSAPP_NUMBER')),
    ],
];
```

### Step 4: Create WhatsApp Service Class

Create `app/Services/WhatsAppService.php`:

```php
<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $this->from = config('services.twilio.whatsapp_from');

        $this->client = new Client($accountSid, $authToken);
    }

    /**
     * Send a text message via WhatsApp
     *
     * @param string $to Recipient phone number in E.164 format (e.g., +1234567890)
     * @param string $message Message content
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance|null
     */
    public function sendMessage(string $to, string $message)
    {
        try {
            // Ensure phone number has whatsapp: prefix
            $to = $this->formatPhoneNumber($to);

            $message = $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message,
                ]
            );

            Log::info('WhatsApp message sent', [
                'to' => $to,
                'message_sid' => $message->sid,
            ]);

            return $message;
        } catch (TwilioException $e) {
            Log::error('WhatsApp message failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send a media message (image, document, etc.)
     *
     * @param string $to Recipient phone number
     * @param string $mediaUrl URL of the media file
     * @param string|null $body Optional message body
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance|null
     */
    public function sendMedia(string $to, string $mediaUrl, ?string $body = null)
    {
        try {
            $to = $this->formatPhoneNumber($to);

            $params = [
                'from' => $this->from,
                'mediaUrl' => [$mediaUrl],
            ];

            if ($body) {
                $params['body'] = $body;
            }

            $message = $this->client->messages->create($to, $params);

            Log::info('WhatsApp media message sent', [
                'to' => $to,
                'media_url' => $mediaUrl,
                'message_sid' => $message->sid,
            ]);

            return $message;
        } catch (TwilioException $e) {
            Log::error('WhatsApp media message failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send a template message (for business-initiated messages)
     *
     * @param string $to Recipient phone number
     * @param string $templateName Template name
     * @param array $parameters Template parameters
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance|null
     */
    public function sendTemplate(string $to, string $templateName, array $parameters = [])
    {
        try {
            $to = $this->formatPhoneNumber($to);

            // Note: Template messages require approval from WhatsApp
            // For sandbox, you can use pre-approved templates
            $message = $this->client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $this->formatTemplate($templateName, $parameters),
                ]
            );

            return $message;
        } catch (TwilioException $e) {
            Log::error('WhatsApp template message failed', [
                'to' => $to,
                'template' => $templateName,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Format phone number with whatsapp: prefix
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any existing whatsapp: prefix
        $phoneNumber = str_replace('whatsapp:', '', $phoneNumber);

        // Ensure it starts with +
        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        return 'whatsapp:' . $phoneNumber;
    }

    /**
     * Format template message (simplified - in production use approved templates)
     *
     * @param string $templateName
     * @param array $parameters
     * @return string
     */
    protected function formatTemplate(string $templateName, array $parameters): string
    {
        // This is a simplified version
        // In production, use Twilio's Content API for approved templates
        $template = match($templateName) {
            'appointment_reminder' => "Reminder: Your appointment is scheduled for {{date}} at {{time}}.",
            'appointment_confirmation' => "Your appointment has been confirmed for {{date}} at {{time}}.",
            default => "Hello! This is a message from {{business_name}}.",
        };

        foreach ($parameters as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }

        return $template;
    }
}
```

### Step 5: Create Controller for WhatsApp Webhooks

Create `app/Http/Controllers/WhatsAppController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\TwiML\MessagingResponse;
use App\Services\WhatsAppService;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle incoming WhatsApp messages
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        // Get incoming message details
        $from = $request->input('From'); // whatsapp:+1234567890
        $body = $request->input('Body');
        $messageSid = $request->input('MessageSid');
        $numMedia = (int) $request->input('NumMedia', 0);

        // Log incoming message
        Log::info('WhatsApp message received', [
            'from' => $from,
            'body' => $body,
            'message_sid' => $messageSid,
            'has_media' => $numMedia > 0,
        ]);

        // Create TwiML response
        $response = new MessagingResponse();

        // Process the message
        $reply = $this->processMessage($from, $body, $numMedia, $request);

        // Send reply
        if ($reply) {
            $response->message($reply);
        }

        return response($response, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Process incoming message and generate appropriate response
     *
     * @param string $from
     * @param string $body
     * @param int $numMedia
     * @param Request $request
     * @return string|null
     */
    protected function processMessage(string $from, string $body, int $numMedia, Request $request): ?string
    {
        // Remove whatsapp: prefix to get phone number
        $phoneNumber = str_replace('whatsapp:', '', $from);

        // Convert message to lowercase for easier processing
        $message = strtolower(trim($body));

        // Handle media messages
        if ($numMedia > 0) {
            return $this->handleMediaMessage($from, $request);
        }

        // Handle text commands
        return $this->handleTextCommand($from, $message, $phoneNumber);
    }

    /**
     * Handle media messages (images, documents, etc.)
     *
     * @param string $from
     * @param Request $request
     * @return string
     */
    protected function handleMediaMessage(string $from, Request $request): string
    {
        $mediaUrl = $request->input('MediaUrl0');
        $mediaContentType = $request->input('MediaContentType0');

        // You can process the media here
        // For example, save it, analyze it, etc.

        return "Thank you for sending the media! We've received it and will review it shortly.";
    }

    /**
     * Handle text commands
     *
     * @param string $from
     * @param string $message
     * @param string $phoneNumber
     * @return string
     */
    protected function handleTextCommand(string $from, string $message, string $phoneNumber): string
    {
        // Example command handlers
        if (str_contains($message, 'hello') || str_contains($message, 'hi')) {
            return "Hello! 👋 Welcome to our clinic. How can we help you today?\n\n"
                 . "You can:\n"
                 . "• Type 'appointment' to book an appointment\n"
                 . "• Type 'hours' to see our operating hours\n"
                 . "• Type 'services' to see our services\n"
                 . "• Type 'help' for more options";
        }

        if (str_contains($message, 'appointment') || str_contains($message, 'book')) {
            return "To book an appointment, please visit our website or call us directly.\n\n"
                 . "Website: https://yourclinic.com/appointments\n"
                 . "Phone: +1234567890";
        }

        if (str_contains($message, 'hours') || str_contains($message, 'time')) {
            return "Our clinic hours:\n\n"
                 . "Monday - Friday: 9:00 AM - 6:00 PM\n"
                 . "Saturday: 10:00 AM - 4:00 PM\n"
                 . "Sunday: Closed";
        }

        if (str_contains($message, 'services')) {
            return "Our services include:\n\n"
                 . "• Psychology Services\n"
                 . "• Homeopathy Treatments\n"
                 . "• Consultation Services\n\n"
                 . "Visit https://yourclinic.com/services for more details.";
        }

        if (str_contains($message, 'help')) {
            return "Here are the available commands:\n\n"
                 . "• 'appointment' - Book an appointment\n"
                 . "• 'hours' - View operating hours\n"
                 . "• 'services' - See our services\n"
                 . "• 'contact' - Get contact information\n\n"
                 . "For urgent matters, please call us directly.";
        }

        if (str_contains($message, 'contact')) {
            return "Contact Information:\n\n"
                 . "📞 Phone: +1234567890\n"
                 . "📧 Email: info@yourclinic.com\n"
                 . "🌐 Website: https://yourclinic.com\n"
                 . "📍 Address: Your Clinic Address";
        }

        // Default response
        return "Thank you for your message! Our team will get back to you shortly.\n\n"
             . "For immediate assistance, please call us at +1234567890.\n\n"
             . "Type 'help' to see available commands.";
    }

    /**
     * Handle message status callbacks (optional)
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function statusCallback(Request $request)
    {
        $messageSid = $request->input('MessageSid');
        $status = $request->input('MessageStatus');

        Log::info('WhatsApp message status update', [
            'message_sid' => $messageSid,
            'status' => $status,
        ]);

        // You can update your database here based on message status
        // e.g., mark message as delivered, failed, etc.

        return response('OK', 200);
    }
}
```

### Step 6: Add Routes

Add to `routes/web.php`:

```php
// WhatsApp webhook routes (should be accessible without authentication)
Route::post('/whatsapp/webhook', [App\Http\Controllers\WhatsAppController::class, 'webhook'])
    ->name('whatsapp.webhook');

Route::post('/whatsapp/status', [App\Http\Controllers\WhatsAppController::class, 'statusCallback'])
    ->name('whatsapp.status');
```

**Important:** For production, you should add CSRF protection exclusion for these routes in `app/Http/Middleware/VerifyCsrfToken.php`:

```php
protected $except = [
    'whatsapp/webhook',
    'whatsapp/status',
];
```

---

## Sending Messages

### Example 1: Send Appointment Reminder

```php
use App\Services\WhatsAppService;

class AppointmentController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function sendReminder(Appointment $appointment)
    {
        $patient = $appointment->patient;
        $phoneNumber = $patient->phone; // Ensure this is in E.164 format

        $message = "Hello {$patient->name},\n\n"
                 . "This is a reminder for your appointment:\n\n"
                 . "Date: {$appointment->appointment_date->format('F d, Y')}\n"
                 . "Time: {$appointment->appointment_time}\n"
                 . "Doctor: {$appointment->doctor->name}\n\n"
                 . "Please arrive 15 minutes early.\n\n"
                 . "Reply 'CONFIRM' to confirm or 'CANCEL' to cancel.";

        try {
            $this->whatsappService->sendMessage($phoneNumber, $message);
            
            return response()->json([
                'success' => true,
                'message' => 'Reminder sent successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reminder: ' . $e->getMessage(),
            ], 500);
        }
    }
}
```

### Example 2: Send Appointment Confirmation

```php
public function sendConfirmation(Appointment $appointment)
{
    $patient = $appointment->patient;
    
    $message = "✅ Appointment Confirmed!\n\n"
             . "Dear {$patient->name},\n\n"
             . "Your appointment has been confirmed:\n\n"
             . "📅 Date: {$appointment->appointment_date->format('F d, Y')}\n"
             . "🕐 Time: {$appointment->appointment_time}\n"
             . "👨‍⚕️ Doctor: {$appointment->doctor->name}\n\n"
             . "We look forward to seeing you!";

    $this->whatsappService->sendMessage($patient->phone, $message);
}
```

### Example 3: Send Media (e.g., Invoice PDF)

```php
public function sendInvoice(Appointment $appointment)
{
    $patient = $appointment->patient;
    
    // Generate invoice PDF (you'll need to implement this)
    $invoiceUrl = route('appointments.invoice', $appointment->id);
    
    $message = "Your invoice is ready! Please find it attached.";
    
    $this->whatsappService->sendMedia(
        $patient->phone,
        $invoiceUrl,
        $message
    );
}
```

---

## Receiving Messages

### Webhook Flow

1. Customer sends WhatsApp message to your Twilio number
2. Twilio receives the message
3. Twilio sends HTTP POST request to your webhook URL
4. Your Laravel application processes the message
5. Your application sends TwiML response back to Twilio
6. Twilio sends the reply to the customer

### Message Processing

The `WhatsAppController` handles incoming messages. You can extend it to:
- Save messages to database
- Create support tickets
- Link messages to patient records
- Trigger automated workflows

### Example: Save Messages to Database

Create migration:

```php
php artisan make:migration create_whatsapp_messages_table
```

Migration file:

```php
Schema::create('whatsapp_messages', function (Blueprint $table) {
    $table->id();
    $table->string('from_number');
    $table->string('to_number');
    $table->text('body')->nullable();
    $table->string('message_sid')->unique();
    $table->string('status')->default('received');
    $table->integer('num_media')->default(0);
    $table->json('media_urls')->nullable();
    $table->foreignId('patient_id')->nullable()->constrained();
    $table->timestamps();
});
```

Update controller to save messages:

```php
use App\Models\WhatsAppMessage;
use App\Models\Patient;

public function webhook(Request $request)
{
    // ... existing code ...

    // Save message to database
    $whatsappMessage = WhatsAppMessage::create([
        'from_number' => $from,
        'to_number' => $request->input('To'),
        'body' => $body,
        'message_sid' => $messageSid,
        'status' => 'received',
        'num_media' => $numMedia,
        'media_urls' => $this->getMediaUrls($request),
        'patient_id' => $this->findPatientByPhone($phoneNumber)?->id,
    ]);

    // ... rest of the code ...
}

protected function getMediaUrls(Request $request): array
{
    $urls = [];
    $numMedia = (int) $request->input('NumMedia', 0);
    
    for ($i = 0; $i < $numMedia; $i++) {
        $urls[] = [
            'url' => $request->input("MediaUrl{$i}"),
            'content_type' => $request->input("MediaContentType{$i}"),
        ];
    }
    
    return $urls;
}

protected function findPatientByPhone(string $phoneNumber): ?Patient
{
    // Remove whatsapp: prefix and clean phone number
    $phoneNumber = str_replace('whatsapp:', '', $phoneNumber);
    
    return Patient::where('phone', 'like', "%{$phoneNumber}%")->first();
}
```

---

## Webhook Configuration

### Development (Using ngrok)

1. Install ngrok: [https://ngrok.com/download](https://ngrok.com/download)
2. Start your Laravel development server:
   ```bash
   php artisan serve
   ```
3. In another terminal, start ngrok:
   ```bash
   ngrok http 8000
   ```
4. Copy the HTTPS URL (e.g., `https://abc123.ngrok.io`)
5. Configure webhook in Twilio Console:
   - Go to **Messaging** → **Settings** → **WhatsApp Sandbox Settings**
   - Set webhook URL: `https://abc123.ngrok.io/whatsapp/webhook`
   - Set HTTP method: `POST`

### Production

1. Deploy your Laravel application to a server with HTTPS
2. Configure webhook URL in Twilio Console:
   - Production URL: `https://yourdomain.com/whatsapp/webhook`
   - HTTP method: `POST`

### Webhook Security

Add webhook validation in your controller:

```php
use Twilio\Security\RequestValidator;

public function webhook(Request $request)
{
    // Validate webhook signature (recommended for production)
    $validator = new RequestValidator(env('TWILIO_AUTH_TOKEN'));
    $url = $request->fullUrl();
    $signature = $request->header('X-Twilio-Signature', '');
    
    if (!$validator->validate($signature, $url, $request->all())) {
        Log::warning('Invalid Twilio webhook signature');
        abort(403, 'Invalid signature');
    }

    // ... rest of webhook code ...
}
```

---

## Advanced Features

### 1. Queue Messages

Use Laravel queues for better performance:

```php
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendWhatsAppMessage;

// In your controller
Queue::push(new SendWhatsAppMessage($phoneNumber, $message));
```

Create job:

```php
php artisan make:job SendWhatsAppMessage
```

```php
namespace App\Jobs;

use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $phoneNumber,
        public string $message
    ) {}

    public function handle(WhatsAppService $whatsappService)
    {
        $whatsappService->sendMessage($this->phoneNumber, $this->message);
    }
}
```

### 2. Scheduled Reminders

Create scheduled task in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Send appointment reminders 24 hours before
    $schedule->call(function () {
        $appointments = Appointment::whereDate('appointment_date', now()->addDay())
            ->where('reminder_sent', false)
            ->get();

        foreach ($appointments as $appointment) {
            dispatch(new SendWhatsAppReminder($appointment));
            $appointment->update(['reminder_sent' => true]);
        }
    })->dailyAt('09:00');
}
```

### 3. Conversation Threading

Track conversations:

```php
Schema::create('whatsapp_conversations', function (Blueprint $table) {
    $table->id();
    $table->string('phone_number');
    $table->foreignId('patient_id')->nullable()->constrained();
    $table->string('status')->default('active'); // active, resolved, closed
    $table->timestamp('last_message_at')->nullable();
    $table->timestamps();
});
```

### 4. AI-Powered Responses

Integrate with OpenAI or similar:

```php
use OpenAI\Laravel\Facades\OpenAI;

protected function handleTextCommand(string $from, string $message, string $phoneNumber): string
{
    // For complex queries, use AI
    if (strlen($message) > 50) {
        return $this->generateAIResponse($message);
    }

    // ... existing command handling ...
}

protected function generateAIResponse(string $message): string
{
    $response = OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are a helpful clinic assistant. Provide brief, friendly responses.',
            ],
            [
                'role' => 'user',
                'content' => $message,
            ],
        ],
        'max_tokens' => 150,
    ]);

    return $response->choices[0]->message->content;
}
```

---

## Best Practices

### 1. Phone Number Format
- Always use E.164 format: `+1234567890`
- Store phone numbers consistently in your database
- Validate phone numbers before sending

### 2. Message Templates
- Use approved WhatsApp Business templates for initial messages
- Keep messages concise and clear
- Include clear call-to-actions

### 3. Error Handling
- Always wrap API calls in try-catch blocks
- Log errors for debugging
- Provide fallback mechanisms

### 4. Rate Limiting
- Respect WhatsApp rate limits
- Use queues for bulk messaging
- Implement retry logic with exponential backoff

### 5. Privacy & Compliance
- Get consent before sending messages
- Provide opt-out options
- Comply with GDPR and local regulations
- Don't send sensitive medical information via WhatsApp

### 6. Testing
- Always test in sandbox first
- Test webhook handling thoroughly
- Test error scenarios
- Monitor message delivery rates

---

## Troubleshooting

### Common Issues

#### 1. Messages Not Sending
**Symptoms:** No error but message not received

**Solutions:**
- Check phone number format (must be E.164)
- Verify recipient has joined WhatsApp sandbox
- Check Twilio account balance
- Verify webhook is configured correctly

#### 2. Webhook Not Receiving Messages
**Symptoms:** Messages sent but webhook not triggered

**Solutions:**
- Verify webhook URL is publicly accessible
- Check webhook URL in Twilio Console
- Ensure route is not protected by authentication
- Check server logs for errors
- Verify ngrok is running (for development)

#### 3. 24-Hour Window Expired
**Symptoms:** Cannot send free-form messages

**Solutions:**
- Use approved message templates
- Wait for customer to send a new message
- Use template messages for business-initiated communication

#### 4. Media Messages Not Working
**Symptoms:** Media not received or displayed

**Solutions:**
- Verify media URL is publicly accessible
- Check file size (max 5MB for WhatsApp)
- Ensure correct content type
- Verify media format is supported

### Debugging Tips

1. **Enable Logging:**
   ```php
   Log::debug('WhatsApp Debug', [
       'request' => $request->all(),
       'from' => $request->input('From'),
       'body' => $request->input('Body'),
   ]);
   ```

2. **Check Twilio Logs:**
   - Go to Twilio Console → Monitor → Logs
   - Filter by WhatsApp messages
   - Check message status and error codes

3. **Test Webhook Locally:**
   ```bash
   # Use ngrok to expose local server
   ngrok http 8000
   
   # Test webhook with curl
   curl -X POST https://your-ngrok-url.ngrok.io/whatsapp/webhook \
     -d "From=whatsapp:+1234567890" \
     -d "Body=Hello" \
     -d "MessageSid=test123"
   ```

---

## Production Checklist

Before going live:

- [ ] Register WhatsApp Business number (not sandbox)
- [ ] Get message templates approved by WhatsApp
- [ ] Set up proper webhook URL with HTTPS
- [ ] Implement webhook signature validation
- [ ] Set up error monitoring and alerts
- [ ] Configure message queuing for reliability
- [ ] Test all message types (text, media, templates)
- [ ] Set up database logging for messages
- [ ] Implement rate limiting
- [ ] Add opt-out functionality
- [ ] Review privacy and compliance requirements
- [ ] Set up backup webhook URL
- [ ] Monitor Twilio account usage and costs

---

## Additional Resources

- [Twilio WhatsApp Documentation](https://www.twilio.com/docs/whatsapp)
- [Twilio PHP SDK](https://www.twilio.com/docs/libraries/php)
- [WhatsApp Business API Documentation](https://developers.facebook.com/docs/whatsapp)
- [Laravel Queue Documentation](https://laravel.com/docs/queues)
- [Twilio Webhook Security](https://www.twilio.com/docs/usage/webhooks/webhooks-security)

---

## Support

For issues or questions:
- Twilio Support: [https://support.twilio.com](https://support.twilio.com)
- Laravel Community: [https://laravel.io](https://laravel.io)
- Stack Overflow: Tag `twilio` and `laravel`

---

**Last Updated:** January 2025
**Version:** 1.0
