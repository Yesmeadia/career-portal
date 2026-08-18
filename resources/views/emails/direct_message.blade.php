<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $customSubject }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f3f4f6; color: #1f2937; margin: 0; padding: 24px 12px; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
        .top-bar { background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 24px 32px; text-align: center; color: #ffffff; }
        .top-bar h1 { margin: 0; font-size: 20px; font-weight: 700; }
        .top-bar p { margin: 4px 0 0 0; font-size: 12px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 32px; }
        .greeting { font-size: 18px; font-weight: 700; color: #111827; margin-top: 0; }
        .text { font-size: 14px; line-height: 1.6; color: #374151; margin-bottom: 16px; }
        .message-card { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin: 20px 0; font-size: 14px; line-height: 1.7; color: #1e293b; }
        .meta-strip { background-color: #ecfdf5; border-radius: 8px; padding: 10px 16px; font-size: 12px; color: #047857; font-weight: 600; margin-bottom: 20px; display: flex; justify-content: space-between; }
        .footer { background-color: #f9fafb; padding: 20px 32px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="top-bar">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="max-height: 48px; width: auto; margin-bottom: 10px; display: inline-block;">
            <h1>RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</h1>
            <p>Official Candidate Communication</p>
        </div>

        <div class="content">
            <div class="meta-strip">
                <span>Position: {{ $application->vacancy->title ?? 'Application' }}</span>
                <span>Ref: {{ $application->reference_no }}</span>
            </div>

            <h2 class="greeting">Dear {{ $application->first_name }} {{ $application->last_name }},</h2>

            <div class="message-card">
                {!! nl2br(e($customMessage)) !!}
            </div>

            <p class="text" style="margin-top: 28px;">Best regards,<br>
            <strong>{{ $senderName ?? 'HR & Recruitment Team' }}</strong><br>
            RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH. All rights reserved.<br>
            Sent via Institutional Career Portal.
        </div>
    </div>
</body>
</html>
