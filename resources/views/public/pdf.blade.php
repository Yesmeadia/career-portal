<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form — {{ $application->reference_no }} — {{ $application->full_name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        :root {
            --primary: #0F172A;
            --accent: #21255E;
            --accent-dark: #191c49;
            --border: #E2E8F0;
            --text-main: #1E293B;
            --text-muted: #64748B;
            --bg-light: #F8FAFC;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            background-color: #F1F5F9;
            font-size: 13px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print-bar {
            background-color: #0F172A;
            color: #FFFFFF;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .no-print-bar h3 {
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--accent);
            color: #0F172A;
        }

        .btn-primary:hover {
            background-color: #00E676;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.15);
            color: #FFFFFF;
            margin-right: 8px;
        }

        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.25);
        }

        .pdf-container {
            max-width: 800px;
            margin: 30px auto;
            background: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #E2E8F0;
            position: relative;
        }

        /* Printable Sheet Styling */
        .sheet-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 18px;
            margin-bottom: 24px;
        }

        .org-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .org-logo-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .org-info h1 {
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        .org-info p {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .doc-badge {
            text-align: right;
        }

        .doc-type-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--accent-dark);
            background: #E6F9F0;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 6px;
        }

        .ref-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
        }

        .candidate-hero-grid {
            display: grid;
            grid-template-columns: 1fr 120px;
            gap: 20px;
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 24px;
        }

        .hero-details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .hero-details-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label-cell {
            width: 130px;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .value-cell {
            font-weight: 700;
            color: var(--primary);
            font-size: 13px;
        }

        .photo-box {
            width: 110px;
            height: 130px;
            border: 2px dashed #CBD5E1;
            border-radius: 8px;
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section-block {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--primary);
            background: #F1F5F9;
            padding: 6px 12px;
            border-left: 4px solid var(--accent);
            border-radius: 0 6px 6px 0;
            margin-bottom: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 24px;
        }

        .info-item {
            border-bottom: 1px solid #F1F5F9;
            padding-bottom: 6px;
        }

        .info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--primary);
            word-break: break-word;
        }

        .full-width {
            grid-column: span 2;
        }

        .text-box-content {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 12px;
            line-height: 1.6;
            color: #334155;
            white-space: pre-line;
        }

        .declaration-box {
            background: #F8FAFC;
            border: 1px border #E2E8F0;
            border-radius: 8px;
            padding: 14px;
            font-size: 11px;
            color: #475569;
            margin-top: 20px;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
            padding-top: 20px;
        }

        .signature-line {
            border-top: 1px solid #94A3B8;
            text-align: center;
            padding-top: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
        }

        .sheet-footer {
            margin-top: 30px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 10px;
            color: var(--text-muted);
        }

        /* PRINT MEDIA OVERRIDES */
        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                background: #FFFFFF !important;
            }

            .pdf-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>

<body>

    {{-- Top Action Bar --}}
    <div class="no-print-bar">
        <h3>
            <span class="material-symbols-outlined" style="color: #00CC68;">description</span>
            Official Candidate Application Form PDF
        </h3>
        <div>
            <a href="javascript:history.back()" class="btn-action btn-secondary">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                Back
            </a>
            <button onclick="window.print()" class="btn-action btn-primary">
                <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                Download / Print PDF
            </button>
        </div>
    </div>

    {{-- Printable Application Form Container --}}
    <div class="pdf-container">

        {{-- Institutional Sheet Header --}}
        <div class="sheet-header">
            <div class="org-brand">
                <img src="{{ asset('logo.png') }}" alt="Logo"
                    style="max-height: 48px; width: auto; object-fit: contain;">
                <div class="org-info">
                    <h1>RAZA UL ULOOM ISLAMIA HSS &mdash; POONCH</h1>
                    <p>Institutional Career &amp; Recruitment Portal &bull; Official Registration Record</p>
                </div>
            </div>
            <div class="doc-badge">
                <div class="doc-type-title">CANDIDATE FORM</div>
                <div class="ref-code">{{ $application->reference_no }}</div>
            </div>
        </div>

        {{-- Candidate Hero Overview --}}
        <div class="candidate-hero-grid">
            <div>
                <table class="hero-details-table">
                    <tr>
                        <td class="label-cell">Applicant Name:</td>
                        <td class="value-cell">{{ $application->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Applied Role:</td>
                        <td class="value-cell">{{ $application->vacancy->title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Department:</td>
                        <td class="value-cell">{{ $application->vacancy->department->name ?? 'General' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Submission Date:</td>
                        <td class="value-cell">
                            {{ $application->created_at ? $application->created_at->format('d F Y, h:i A') : 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Photo Box --}}
            <div class="photo-box">
                @if($application->photo_path)
                    <img src="{{ $application->photo_url }}" alt="{{ $application->full_name }}">
                @else
                    <div style="text-align: center; color: var(--text-muted); font-size: 10px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: #CBD5E1;">account_box</span>
                        <br>Passport Photo
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 1: Personal & Contact Information --}}
        <div class="section-block">
            <div class="section-title">1. Personal &amp; Contact Details</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">First Name</div>
                    <div class="info-value">{{ $application->first_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Name</div>
                    <div class="info-value">{{ $application->last_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $application->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Contact Phone</div>
                    <div class="info-value">{{ $application->phone }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">WhatsApp Number</div>
                    <div class="info-value">{{ $application->whatsapp_number ?? $application->phone }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date of Birth &amp; Age</div>
                    <div class="info-value">
                        {{ $application->date_of_birth ? $application->date_of_birth->format('d/m/Y') : 'N/A' }}
                        @if($application->age)
                            <span style="font-weight:700; color:#21255E;">({{ $application->age }} Yrs)</span>
                        @endif
                        @if($application->gender)&bull; {{ ucfirst($application->gender) }}@endif
                    </div>
                </div>
                <div class="info-item full-width">
                    <div class="info-label">Full Residential Address</div>
                    <div class="info-value">
                        {{ $application->address ?? 'N/A' }}
                        @if($application->city), {{ $application->city }}@endif
                        @if($application->state), {{ $application->state }}@endif
                        @if($application->country), {{ $application->country }}@endif
                        @if($application->pin_code) — {{ $application->pin_code }}@endif
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: Qualifications & Professional Background --}}
        <div class="section-block">
            <div class="section-title">2. Qualification &amp; Professional Experience</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Highest Qualification</div>
                    <div class="info-value">{{ $application->highest_qualification ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Experience (Years)</div>
                    <div class="info-value">
                        {{ $application->experience_years ? $application->experience_years . ' Years' : 'Fresher / N/A' }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Current / Most Recent Employer</div>
                    <div class="info-value">{{ $application->current_employer ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Notice Period</div>
                    <div class="info-value">{{ $application->notice_period ?? 'Immediate / N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Current Salary</div>
                    <div class="info-value">{{ $application->current_salary ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Expected Salary</div>
                    <div class="info-value">{{ $application->expected_salary ?? 'N/A' }}</div>
                </div>
                @if($application->skills)
                    <div class="info-item full-width">
                        <div class="info-label">Key Skills &amp; Competencies</div>
                        <div class="info-value">{{ $application->skills }}</div>
                    </div>
                @endif
                @if($application->languages)
                    <div class="info-item full-width">
                        <div class="info-label">Languages Known</div>
                        <div class="info-value">{{ $application->languages }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- SECTION 3: Additional Links & Cover Statement --}}
        @if($application->linkedin_url || $application->portfolio_url || $application->cover_letter)
            <div class="section-block">
                <div class="section-title">3. Links &amp; Cover Statement</div>
                <div class="info-grid">
                    @if($application->linkedin_url)
                        <div class="info-item">
                            <div class="info-label">LinkedIn Profile</div>
                            <div class="info-value">{{ $application->linkedin_url }}</div>
                        </div>
                    @endif
                    @if($application->portfolio_url)
                        <div class="info-item">
                            <div class="info-label">Portfolio / Work URL</div>
                            <div class="info-value">{{ $application->portfolio_url }}</div>
                        </div>
                    @endif
                    @if($application->cover_letter)
                        <div class="info-item full-width">
                            <div class="info-label">Cover Letter / Statement of Purpose</div>
                            <div class="text-box-content">{{ $application->cover_letter }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- SECTION 4: Interview Notes & Assessment --}}
        <div class="section-block">
            <div class="section-title">4. Interview Assessment &amp; Evaluation Notes</div>
            <div class="info-grid">
                @if($application->admin_notes)
                    <div class="info-item full-width">
                        <div class="info-label">Interview / Candidate Evaluation Notes</div>
                        <div class="text-box-content" style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:6px; padding:10px; font-size:12px; line-height:1.6; color:#0F172A; white-space:pre-line;">
                            {{ $application->admin_notes }}
                        </div>
                    </div>
                @endif

                @if($application->interviews && $application->interviews->count() > 0)
                    <div class="info-item full-width" style="margin-top:6px;">
                        <div class="info-label">Interview Rounds &amp; Panel Feedback</div>
                        <table style="width:100%; border-collapse:collapse; font-size:11px; margin-top:4px;">
                            <thead>
                                <tr style="background:#F1F5F9; text-align:left; color:#475569;">
                                    <th style="padding:6px 8px; border:1px solid #E2E8F0;">Date &amp; Time</th>
                                    <th style="padding:6px 8px; border:1px solid #E2E8F0;">Mode / Location</th>
                                    <th style="padding:6px 8px; border:1px solid #E2E8F0;">Panel Members</th>
                                    <th style="padding:6px 8px; border:1px solid #E2E8F0;">Status / Score</th>
                                    <th style="padding:6px 8px; border:1px solid #E2E8F0;">Feedback / Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($application->interviews as $inv)
                                    <tr>
                                        <td style="padding:6px 8px; border:1px solid #E2E8F0; font-weight:600;">
                                            {{ \Carbon\Carbon::parse($inv->scheduled_date)->format('d M Y') }} at {{ \Carbon\Carbon::parse($inv->scheduled_time)->format('h:i A') }}
                                        </td>
                                        <td style="padding:6px 8px; border:1px solid #E2E8F0;">
                                            {{ ucfirst(str_replace('_', ' ', $inv->location_type)) }}
                                            @if($inv->location_address_or_link) &bull; {{ Str::limit($inv->location_address_or_link, 30) }}@endif
                                        </td>
                                        <td style="padding:6px 8px; border:1px solid #E2E8F0;">
                                            {{ $inv->panel_members ?: '—' }}
                                        </td>
                                        <td style="padding:6px 8px; border:1px solid #E2E8F0;">
                                            <span style="font-weight:700; text-transform:uppercase; font-size:10px;">{{ $inv->status }}</span>
                                            @if($inv->score !== null) &bull; <strong>{{ $inv->score }}/100</strong>@endif
                                        </td>
                                        <td style="padding:6px 8px; border:1px solid #E2E8F0;">
                                            {{ $inv->feedback ?: ($inv->remarks ?: '—') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(!$application->admin_notes)
                    <div class="info-item full-width">
                        <div class="info-label">Interviewer Assessment &amp; Recommendation Notes</div>
                        <div style="min-height:80px; border:1px dashed #CBD5E1; border-radius:6px; background:#FAFAFA; padding:10px; color:#94A3B8; font-style:italic; font-size:11px;">
                            Interview remarks, panel scoring, and candidate recommendation notes will appear here once recorded in the admin portal.
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="sheet-footer">
            <div>
                System Reference: <strong>{{ $application->reference_no }}</strong> &bull; Generated via Career Portal
            </div>
            <div>
                Page 1 of 1 &bull; Official Document
            </div>
        </div>

    </div>

    {{-- Auto Trigger Print dialog on load if download query param present --}}
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('auto_print')) {
                setTimeout(() => {
                    window.print();
                }, 400);
            }
        });
    </script>

</body>

</html>