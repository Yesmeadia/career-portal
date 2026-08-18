<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Candidate Application Alert</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', 'Helvetica Neue', Arial, sans-serif; background-color: #EFF6FF; color: #0F172A; padding: 32px 12px; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 0 auto; }
        .card { background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header { background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 36px 40px; text-align: center; border-bottom: 4px solid #21255E; }
        .header img { max-height: 52px; width: auto; margin-bottom: 16px; display: block; margin-left: auto; margin-right: auto; }
        .header-school { font-size: 18px; font-weight: 800; color: #ffffff; letter-spacing: -0.3px; line-height: 1.3; margin-bottom: 6px; }
        .header-subtitle { font-size: 11px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; }
        .body { padding: 40px; }
        .alert-row { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .alert-icon { width: 36px; height: 36px; border-radius: 50%; background: #DCFCE7; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .alert-badge { display: inline-flex; align-items: center; gap: 6px; background: #DCFCE7; color: #16A34A; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding: 6px 14px; border-radius: 9999px; border: 1px solid #BBF7D0; }
        .alert-dot { width: 7px; height: 7px; border-radius: 50%; background: #16A34A; display: inline-block; }
        h2.title { font-size: 22px; font-weight: 800; color: #0F172A; margin-bottom: 8px; letter-spacing: -0.4px; }
        p.text { font-size: 14px; line-height: 1.7; color: #475569; margin-bottom: 14px; }
        .ref-highlight { display: inline-block; background: linear-gradient(135deg, #0F172A, #1E293B); color: #21255E; font-family: 'Courier New', monospace; font-size: 14px; font-weight: 700; padding: 4px 12px; border-radius: 6px; letter-spacing: 2px; }
        .details-box { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; overflow: hidden; margin: 24px 0; }
        .details-header { background: #F1F5F9; padding: 12px 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748B; border-bottom: 1px solid #E2E8F0; }
        .details-row { display: flex; border-bottom: 1px solid #E2E8F0; }
        .details-row:last-child { border-bottom: none; }
        .details-label { width: 38%; padding: 12px 20px; font-size: 12px; font-weight: 600; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.4px; background: #FAFBFC; border-right: 1px solid #E2E8F0; display: flex; align-items: center; }
        .details-value { flex: 1; padding: 12px 20px; font-size: 13px; font-weight: 700; color: #0F172A; display: flex; align-items: center; }
        .btn-wrap { text-align: center; margin: 32px 0 20px; }
        .btn { display: inline-block; background: #21255E; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 9999px; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.8px; }
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
                <div class="header-subtitle">Internal Recruitment Alert</div>
            </div>

            {{-- Body --}}
            <div class="body">
                <div><span class="alert-badge"><span class="alert-dot"></span> New Submission</span></div>
                <br>
                <h2 class="title">New Candidate Application Received</h2>
                <p class="text">A new candidate has submitted an application for an open vacancy at <strong>RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</strong>. Please review the applicant's profile at your earliest convenience.</p>

                {{-- Details Box --}}
                <div class="details-box">
                    <div class="details-header">Candidate Details</div>
                    <div class="details-row">
                        <div class="details-label">Reference No</div>
                        <div class="details-value"><span class="ref-highlight">{{ $application->reference_no }}</span></div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Candidate Name</div>
                        <div class="details-value">{{ $application->first_name }} {{ $application->last_name }}</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Applied For</div>
                        <div class="details-value">{{ $application->vacancy->title ?? 'N/A' }}</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Email</div>
                        <div class="details-value">{{ $application->email }}</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Phone</div>
                        <div class="details-value">{{ $application->phone }}</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Qualification</div>
                        <div class="details-value">{{ $application->highest_qualification ?: 'Not specified' }}</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Experience</div>
                        <div class="details-value">{{ $application->experience_years ?: 'Fresh / Entry Level' }}</div>
                    </div>
                </div>

                <p class="text">You can review the complete candidate profile, credentials, and attached resume from the institutional admin dashboard.</p>

                <div class="btn-wrap">
                    <a href="{{ route('schooladmin.applications.show', $application) }}" class="btn" target="_blank">View Candidate in Admin Portal</a>
                </div>
            </div>

            {{-- Footer --}}
            <div class="footer-wrap">
                <div class="footer-brand">RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</div>
                &copy; {{ date('Y') }} All rights reserved. Internal campus recruitment alert.
            </div>
        </div>
    </div>
</body>
</html>
