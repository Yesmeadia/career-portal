<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #F8FAFC; color: #0F172A; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; padding: 32px; border-radius: 16px; border: 1px solid #E2E8F0; }
        .header { border-bottom: 1px solid #E2E8F0; padding-bottom: 20px; margin-bottom: 24px; text-align: center; }
        .brand { font-size: 24px; font-weight: 700; color: #2563EB; }
        .details { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .details-item { margin-bottom: 12px; }
        .details-label { font-size: 12px; font-weight: bold; text-transform: uppercase; color: #166534; }
        .details-val { font-size: 16px; font-weight: 600; color: #0f172a; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="{{ asset('logo.png') }}" alt="Logo" style="max-height: 48px; width: auto; margin-bottom: 10px; display: inline-block;">
            <div class="brand">RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</div>
            <p style="color: #64748B; margin-top: 4px;">Interview Schedule Notification</p>
        </div>

        <h2>You Are Invited for an Interview!</h2>
        <p>Dear {{ $interview->application->first_name }},</p>
        <p>We are pleased to invite you for an interview for the role of <strong>{{ $interview->application->vacancy->title }}</strong>.</p>

        <div class="details">
            <div class="details-item">
                <div class="details-label">Date & Time</div>
                <div class="details-val">{{ $interview->scheduled_date->format('M d, Y') }} at {{ date('h:i A', strtotime($interview->scheduled_time)) }}</div>
            </div>
            <div class="details-item">
                <div class="details-label">Interview Format</div>
                <div class="details-val">{{ ucfirst(str_replace('_', ' ', $interview->location_type)) }}</div>
            </div>
            <div class="details-item">
                <div class="details-label">Location / Online Link</div>
                <div class="details-val">{{ $interview->location_address_or_link }}</div>
            </div>
            @if($interview->remarks)
            <div class="details-item">
                <div class="details-label">Instructions / Remarks</div>
                <div class="details-val" style="font-weight: normal;">{{ $interview->remarks }}</div>
            </div>
            @endif
        </div>

        <p>Please ensure you are prepared and present 10 minutes prior to the scheduled time.</p>

        <p style="margin-top: 24px;">Best regards,<br><strong>HR &amp; Recruitment Team</strong><br>RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</p>
    </div>
</body>
</html>
