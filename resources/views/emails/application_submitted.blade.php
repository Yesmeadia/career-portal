<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Received</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', 'Helvetica Neue', Arial, sans-serif; background-color: #ECFDF5; color: #0F172A; padding: 32px 12px; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 36px 40px; text-align: center; border-bottom: 4px solid #21255E; }
        .header img { max-height: 52px; width: auto; margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto; }
        .header-school { font-size: 18px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px; line-height: 1.3; margin-bottom: 6px; }
        .header-subtitle { font-size: 11px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; }
        .body { padding: 40px; }
        .badge { display: inline-flex; align-items: center; gap: 6px; background: #DCFCE7; color: #16A34A; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px; border: 1px solid #BBF7D0; margin-bottom: 20px; }
        .badge-dot { width: 7px; height: 7px; border-radius: 50%; background: #16A34A; display: inline-block; }
        h2.title { font-size: 22px; font-weight: 800; color: #0F172A; margin-bottom: 8px; letter-spacing: -0.4px; }
        p.text { font-size: 14px; line-height: 1.7; color: #475569; margin-bottom: 14px; }
        .ref-box { background: linear-gradient(135deg, #0F172A, #1E293B); border-radius: 14px; padding: 24px; text-align: center; margin: 28px 0; }
        .ref-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: #94A3B8; margin-bottom: 10px; }
        .ref-code { font-family: 'Courier New', monospace; font-size: 28px; font-weight: 800; color: #21255E; letter-spacing: 4px; line-height: 1; }
        .divider { border: none; border-top: 1px solid #F1F5F9; margin: 28px 0; }
        .info-grid { display: table; width: 100%; border-collapse: collapse; background: #F8FAFC; border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; margin: 24px 0; }
        .info-row { display: table-row; }
        .info-row:not(:last-child) .info-cell { border-bottom: 1px solid #E2E8F0; }
        .info-cell { display: table-cell; padding: 12px 18px; font-size: 13px; }
        .info-cell.label { font-weight: 600; color: #94A3B8; width: 38%; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; vertical-align: middle; }
        .info-cell.value { font-weight: 700; color: #0F172A; vertical-align: middle; }
        .btn-wrap { text-align: center; margin: 32px 0 20px; }
        .btn { display: inline-block; background: #21255E; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px; }
        .sign-off { font-size: 14px; color: #475569; line-height: 1.7; margin-top: 28px; border-top: 1px solid #F1F5F9; padding-top: 24px; }
        .sign-off strong { color: #0F172A; font-weight: 700; }
        .footer-wrap { background: #F8FAFC; border-radius: 0 0 20px 20px; padding: 20px 40px; text-align: center; font-size: 11.5px; color: #94A3B8; line-height: 1.6; border-top: 1px solid #E2E8F0; }
        .footer-brand { font-weight: 700; color: #64748B; margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            {{-- Header --}}
            <div class="header">
                <img src="{{ asset('logo.png') }}" alt="School Logo">
                <div class="header-school">RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</div>
                <div class="header-subtitle">Institutional Career Portal</div>
            </div>

            {{-- Body --}}
            <div class="body">
                <div class="badge"><span class="badge-dot"></span> Application Received</div>
                <h2 class="title">Your Application Was Submitted!</h2>
                <p class="text">Dear <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>,</p>
                <p class="text">Thank you for applying for the position of <strong>{{ $application->vacancy->title ?? 'Position' }}</strong>. Your application has been registered in our recruitment system and is currently under review.</p>

                {{-- Reference Box --}}
                <div class="ref-box">
                    <div class="ref-label">Your Application Reference Number</div>
                    <div class="ref-code">{{ $application->reference_no }}</div>
                </div>

                {{-- Details Table --}}
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-cell label">Position</div>
                        <div class="info-cell value">{{ $application->vacancy->title ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell label">Institution</div>
                        <div class="info-cell value">RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell label">Email</div>
                        <div class="info-cell value">{{ $application->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell label">Phone</div>
                        <div class="info-cell value">{{ $application->phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-cell label">Submitted On</div>
                        <div class="info-cell value">{{ $application->created_at ? $application->created_at->format('M d, Y · h:i A') : date('M d, Y') }}</div>
                    </div>
                </div>

                <p class="text">Please keep your reference number safely. You will receive email updates as your application progresses through our evaluation pipeline.</p>

                <div class="btn-wrap">
                    <a href="{{ route('applications.track') }}?reference_no={{ $application->reference_no }}" class="btn" target="_blank">Track My Application</a>
                </div>

                <div class="sign-off">
                    Best regards,<br>
                    <strong>HR &amp; Recruitment Team</strong><br>
                    RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH
                </div>
            </div>

            {{-- Footer --}}
            <div class="footer-wrap">
                <div class="footer-brand">RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</div>
                &copy; {{ date('Y') }} All rights reserved. Automated notification &mdash; please do not reply.
            </div>
        </div>
    </div>
</body>
</html>