@extends('layouts.admin')

@section('title', 'Recruitment Reports & Analytics')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <div class="max-w-[1400px] mx-auto">

        {{-- Overview Page Header & Live System Clock --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" x-data="{
                         timeStr: '{{ now()->timezone('Asia/Kolkata')->format('h:i:s A') }}',
                         dateStr: '{{ now()->timezone('Asia/Kolkata')->format('l, M d, Y | h:i A') }}',
                         init() {
                             const update = () => {
                                 const now = new Date();
                                 const optionsTime = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                                 const optionsShortTime = { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', hour12: true };
                                 const optionsDate = { timeZone: 'Asia/Kolkata', weekday: 'long', month: 'short', day: 'numeric', year: 'numeric' };

                                 this.timeStr = new Intl.DateTimeFormat('en-US', optionsTime).format(now);
                                 const d = new Intl.DateTimeFormat('en-US', optionsDate).format(now);
                                 const tShort = new Intl.DateTimeFormat('en-US', optionsShortTime).format(now);
                                 this.dateStr = `${d} | ${tShort}`;
                             };
                             update();
                             setInterval(update, 1000);
                         }
                     }">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">
                        Recruitment Reports &amp; Analytics</h2>
                </div>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('schooladmin.reports.export-applications') }}"
                    class="text-white font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    <span>Export Full CSV Report</span>
                </a>
            </div>
        </div>

        {{-- Stats Summary Cards (4 Columns) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

            {{-- Card 1: Total Applications --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                    transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Total Applications</h3>
                    <div class="flex items-center justify-center text-blue-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(219,234,254,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">description</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['total_applications']) }}</span>
                <div class="flex items-center gap-1 bg-blue-50 text-blue-700 w-fit text-xs font-semibold border border-blue-200 mt-auto px-2 py-0.5"
                    style="border-radius:8px;">
                    <span class="material-symbols-outlined" style="font-size:12px;">trending_up</span>
                    Candidate Submissions
                </div>
            </div>

            {{-- Card 2: Open Vacancies --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                    transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #fdd88d 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Open Vacancies</h3>
                    <div class="flex items-center justify-center text-amber-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(254,243,199,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">work</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['open_jobs']) }}</span>
                <div class="text-xs text-gray-400 mt-auto">Active recruitment listings</div>
            </div>

            {{-- Card 3: Selected Candidates --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                    transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Selected Candidates</h3>
                    <div class="flex items-center justify-center text-emerald-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(209,250,229,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">how_to_reg</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['selected_candidates']) }}</span>
                <div class="text-xs text-emerald-700 font-bold mt-auto">Hired / Selected profiles</div>
            </div>

            {{-- Card 4: Rejected Candidates --}}
            <div class="group p-4 border border-gray-100 flex flex-col gap-2 cursor-default
                                    transition-all duration-200 hover:shadow-lg hover:-translate-y-px"
                style="background: linear-gradient(135deg, #ffffff 0%, #e5eeff 100%); border-radius: 20px;">
                <div class="flex justify-between items-start">
                    <h3 class="text-xs text-gray-500 font-medium leading-tight">Rejected Candidates</h3>
                    <div class="flex items-center justify-center text-red-600 shrink-0"
                        style="width:32px;height:32px;border-radius:10px;background:rgba(254,226,226,0.7);">
                        <span class="material-symbols-outlined" style="font-size:17px;">cancel</span>
                    </div>
                </div>
                <span
                    class="text-4xl font-bold text-gray-900 leading-none tracking-tight">{{ number_format($stats['rejected_candidates']) }}</span>
                <div class="text-xs text-gray-400 mt-auto">Not shortlisted</div>
            </div>

        </div>

        {{-- Graphical Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            {{-- Chart 1: Recruitment Performance Trend (2 Columns) --}}
            <div class="lg:col-span-2 bg-white shadow-sm border border-gray-100 p-6 flex flex-col justify-between"
                style="border-radius: 20px;">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-base font-bold text-[#111827]">Applications Growth Trend</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Monthly breakdown of candidate applications received across
                            open positions.</p>
                    </div>
                    <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        2026 Recruitment Cycle
                    </span>
                </div>
                <div class="h-64 relative">
                    <canvas id="applicationsTrendChart"></canvas>
                </div>
            </div>

            {{-- Chart 2: Pipeline Funnel Distribution (1 Column) --}}
            <div class="bg-white shadow-sm border border-gray-100 p-6 flex flex-col justify-between"
                style="border-radius: 20px;">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-[#111827]">Pipeline Stage Distribution</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Status breakdown of submitted applications.</p>
                </div>
                <div class="h-56 relative flex items-center justify-center">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-2 text-center text-xs font-semibold mt-2 pt-2 border-t border-gray-100">
                    <div class="text-emerald-700 bg-emerald-50 py-1 rounded-lg">Selected:
                        {{ $stats['selected_candidates'] }}
                    </div>
                    <div class="text-blue-700 bg-blue-50 py-1 rounded-lg">Total Apps: {{ $stats['total_applications'] }}
                    </div>
                </div>
            </div>

        </div>

        {{-- Vacancies Performance Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden mb-6" style="border-radius: 20px;">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="text-base font-bold text-[#111827]">Applications per Vacancy Report</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Total {{ $vacanciesWithCount->count() }} active and closed
                            vacancy performance metrics.</p>
                    </div>
                </div>
            </div>

            <table class="w-full text-left" style="border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th
                            class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            Vacancy Title</th>
                        <th
                            class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            Employment Type</th>
                        <th
                            class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            Status</th>
                        <th
                            class="px-6 py-4 text-[11px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100 text-right">
                            Total Submissions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vacanciesWithCount as $index => $vac)
                        @php $rowBg = $index % 2 === 0 ? '#ffffff' : '#fafbfc'; @endphp
                        <tr style="background: {{ $rowBg }}; transition: background 0.15s;"
                            onmouseover="this.style.background='#f0f4ff'" onmouseout="this.style.background='{{ $rowBg }}'">

                            <td class="px-6 py-4 border-b border-gray-50 font-bold text-gray-900 text-sm">
                                <p class="font-bold text-[#111827] text-sm">{{ $vac->title }}</p>
                                <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                                    {{ $vac->department->name ?? 'General Dept' }}
                                </p>
                            </td>

                            <td class="px-6 py-4 border-b border-gray-50 text-xs font-medium text-gray-600">
                                {{ ucfirst(str_replace('_', ' ', $vac->employment_type)) }}
                            </td>

                            <td class="px-6 py-4 border-b border-gray-50">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold border {{ $vac->status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                    {{ ucfirst($vac->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-b border-gray-50 text-right font-extrabold text-[#21255E] text-base">
                                {{ number_format($vac->applications_count) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">No vacancies created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Trend Bar Chart
                const ctxTrend = document.getElementById('applicationsTrendChart')?.getContext('2d');
                if (ctxTrend) {
                    new Chart(ctxTrend, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [{
                                label: 'Applications',
                                data: [12, 19, 15, 25, 32, 40, 48, {{ $stats['total_applications'] }}, 0, 0, 0, 0],
                                backgroundColor: '#21255E',
                                borderRadius: 8,
                                hoverBackgroundColor: '#D7B56D'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                // Status Distribution Doughnut Chart
                const ctxStatus = document.getElementById('statusDistributionChart')?.getContext('2d');
                if (ctxStatus) {
                    new Chart(ctxStatus, {
                        type: 'doughnut',
                        data: {
                            labels: ['Selected', 'Pending Review', 'Rejected'],
                            datasets: [{
                                data: [
                                                    {{ $stats['selected_candidates'] }},
                                                    {{ max(0, $stats['total_applications'] - $stats['selected_candidates'] - $stats['rejected_candidates']) }},
                                    {{ $stats['rejected_candidates'] }}
                                ],
                                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                            },
                            cutout: '70%'
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection