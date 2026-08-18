<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', 'Helvetica Neue', Arial, sans-serif; background-color: #F0FDF4; color: #0F172A; padding: 32px 12px; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 36px 40px; text-align: center; border-bottom: 4px solid #21255E; }
        .header img { max-height: 52px; width: auto; margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto; }
        .header-school { font-size: 18px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px; line-height: 1.3; margin-bottom: 6px; }
        .header-subtitle { font-size: 11px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; }
        .body { padding: 40px; }
        h2.title { font-size: 22px; font-weight: 800; color: #0F172A; margin-bottom: 8px; letter-spacing: -0.4px; }
        p.text { font-size: 14px; line-height: 1.7; color: #475569; margin-bottom: 14px; }
        .ref-pill { display: inline-block; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 8px; padding: 6px 14px; font-family: 'Courier New', monospace; font-size: 13px; font-weight: 700; color: #0F172A; }
        .status-card { background: linear-gradient(135deg, #0F172A, #1E293B); border-radius: 16px; padding: 28px 32px; text-align: center; margin: 28px 0; }
        .status-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; color: #94A3B8; margin-bottom: 14px; }
        .status-badge { display: inline-block; background: #21255E; color: #ffffff; font-size: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; padding: 10px 28px; border-radius: 9999px; }
        .interview-card { background: #F0FDF4; border: 2px solid #86EFAC; border-radius: 16px; padding: 24px 28px; margin: 24px 0; text-align: left; }
        .interview-title { font-size: 15px; font-weight: 800; color: #166534; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .interview-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .interview-table td { padding: 8px 0; font-size: 13.5px; vertical-align: top; border-bottom: 1px solid #DCFCE7; }
        .interview-table tr:last-child td { border-bottom: none; }
        .interview-lbl { font-weight: 700; color: #15803D; width: 34%; }
        .interview-val { font-weight: 600; color: #0F172A; }
        .interview-notice { background: #FEF9C3; border-left: 3px solid #CA8A04; padding: 10px 14px; font-size: 12px; color: #713F12; border-radius: 0 8px 8px 0; margin-top: 14px; line-height: 1.5; }
        .remarks { background: #F8FAFC; border-left: 4px solid #21255E; border-radius: 0 10px 10px 0; padding: 16px 20px; margin: 20px 0; }
        .remarks-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; margin-bottom: 6px; }
        .remarks-text { font-size: 13px; color: #334155; font-style: italic; line-height: 1.6; }
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
                <div class="header-subtitle">{{ $newStatus === 'interview_scheduled' ? 'Official Interview Invitation' : 'Application Status Update' }}</div>
            </div>

            {{-- Body --}}
            <div class="body">
                @if($newStatus === 'interview_scheduled')
                    <h2 class="title">You Are Invited for an Interview!</h2>
                    <p class="text">Dear <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>,</p>
                    <p class="text">We are pleased to inform you that your application for the position of <strong>{{ $application->vacancy->title ?? 'the advertised position' }}</strong> (Reference: <span class="ref-pill">{{ $application->reference_no }}</span>) has progressed to the interview stage.</p>

                    {{-- Interview Schedule Details Card --}}
                    @if($interview)
                        <div class="interview-card">
                            <div class="interview-title">
                                Interview Schedule &amp; Appointment Details
                            </div>
                            <table class="interview-table">
                                <tr>
                                    <td class="interview-lbl">Date &amp; Time</td>
                                    <td class="interview-val">
                                        {{ \Carbon\Carbon::parse($interview->scheduled_date)->format('l, d M Y') }} at {{ date('h:i A', strtotime($interview->scheduled_time)) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="interview-lbl">Interview Mode</td>
                                    <td class="interview-val">
                                        {{ ucfirst(str_replace('_', ' ', $interview->location_type)) }}
                                        @if($interview->location_type === 'online')
                                            (Video Conference)
                                        @else
                                            (In-Person)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="interview-lbl">Venue / Link</td>
                                    <td class="interview-val">
                                        @if(filter_var($interview->location_address_or_link, FILTER_VALIDATE_URL) || str_starts_with($interview->location_address_or_link, 'http'))
                                            <a href="{{ $interview->location_address_or_link }}" target="_blank" style="color: #2563EB; text-decoration: underline; word-break: break-all; font-weight: 700;">{{ $interview->location_address_or_link }}</a>
                                        @else
                                            <strong>{{ $interview->location_address_or_link }}</strong>
                                        @endif
                                    </td>
                                </tr>
                                @if(!empty($interview->panel_members))
                                <tr>
                                    <td class="interview-lbl">Panel Members</td>
                                    <td class="interview-val" style="color: #334155;">
                                        {{ $interview->panel_members }}
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($interview->remarks))
                                <tr>
                                    <td class="interview-lbl">Special Instructions</td>
                                    <td class="interview-val" style="color: #334155; font-style: italic;">
                                        {{ $interview->remarks }}
                                    </td>
                                </tr>
                                @endif
                            </table>

                            <div class="interview-notice">
                                <strong>Important:</strong> Please ensure you arrive at the venue or connect to the online meeting link at least <strong>10 minutes prior</strong> to your scheduled time. Keep your original certificates, identity proof, and relevant documents ready for verification.
                            </div>
                        </div>
                    @else
                        {{-- Fallback Status Card if interview record not attached --}}
                        <div class="status-card">
                            <div class="status-label">Your Application Status</div>
                            <div class="status-badge">Interview Scheduled</div>
                        </div>
                    @endif

                @else
                    <h2 class="title">Your Application Status Has Changed</h2>
                    <p class="text">Dear <strong>{{ $application->first_name }} {{ $application->last_name }}</strong>,</p>
                    <p class="text">There is an update regarding your application for the position of <strong>{{ $application->vacancy->title ?? 'Position' }}</strong>. Reference: <span class="ref-pill">{{ $application->reference_no }}</span></p>

                    {{-- Status Card --}}
                    <div class="status-card">
                        <div class="status-label">Your New Application Status</div>
                        <div class="status-badge">{{ ucwords(str_replace('_', ' ', $newStatus)) }}</div>
                    </div>
                @endif

                {{-- Admin Remarks --}}
                @if($application->admin_notes)
                    <div class="remarks">
                        <div class="remarks-label">Recruitment Team Remarks</div>
                        <div class="remarks-text">&ldquo;{{ $application->admin_notes }}&rdquo;</div>
                    </div>
                @endif

                <p class="text">Our hiring committee evaluates candidate profiles based on institutional standards. We appreciate your interest in joining <strong>RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</strong>.</p>

                <div class="btn-wrap">
                    <a href="{{ route('applications.track') }}?reference_no={{ $application->reference_no }}" class="btn" target="_blank">Track Full Application Status</a>
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