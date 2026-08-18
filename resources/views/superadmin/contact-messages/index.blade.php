@extends('layouts.admin')

@section('title', 'Contact Inbox')

@section('content')
    <div class="max-w-[1400px] mx-auto">

        {{-- Overview Page Header & Live System Clock --}}
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
             x-data="{
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
                    <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 38px;">Contact Inbox</h2>
                </div>
            </div>

            {{-- Top Right Actions --}}
            <div class="flex items-center gap-3">
                @if($unreadCount > 0)
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 font-bold shadow-2xs">
                        <span class="material-symbols-outlined text-[18px] text-amber-600">mark_email_unread</span>
                        {{ $unreadCount }} Unread {{ Str::plural('Message', $unreadCount) }}
                    </div>
                @else
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-full font-label-md text-[13px] flex items-center gap-2 font-bold shadow-2xs">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                        Inbox Up to Date
                    </div>
                @endif
            </div>
        </div>

        {{-- Status Navigation Tabs & Search Toolbar --}}
        <div class="bg-white shadow-sm border border-gray-100 p-5 mb-6" style="border-radius: 20px;">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                {{-- Status Tabs --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
                    @php
                        $statusTabs = [
                            '' => 'All Messages',
                            'unread' => 'Unread',
                            'read' => 'Read',
                            'replied' => 'Replied',
                            'archived' => 'Archived',
                        ];
                    @endphp
                    @foreach($statusTabs as $tabKey => $tabLabel)
                        <a href="{{ route('superadmin.contact-messages.index', array_filter(['status' => $tabKey, 'search' => $search])) }}"
                            class="px-4 py-2 rounded-full text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5 {{ ($status === $tabKey || ($tabKey === '' && !$status)) ? 'text-white shadow-sm' : 'bg-gray-100/80 text-gray-600 hover:bg-gray-200/80' }}"
                            style="{{ ($status === $tabKey || ($tabKey === '' && !$status)) ? 'background-color: #21255E;' : '' }}">
                            {{ $tabLabel }}
                            @if($tabKey === 'unread' && $unreadCount > 0)
                                <span class="ml-1 px-2 py-0.5 text-[10px] rounded-full bg-red-500 text-white font-extrabold">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                {{-- Search Input --}}
                <form method="GET" action="{{ route('superadmin.contact-messages.index') }}" class="flex items-center gap-3">
                    @if($status)
                        <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search sender, email..."
                            class="pl-10 pr-4 py-2 rounded-full border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-xs text-gray-700 w-56 sm:w-64 bg-gray-50/50">
                    </div>
                    <button type="submit"
                        class="bg-[#21255E] hover:bg-[#1a1d4b] text-white px-4 py-2 rounded-full text-xs font-bold transition-all shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
                    </button>
                    @if($search)
                        <a href="{{ route('superadmin.contact-messages.index', array_filter(['status' => $status])) }}"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-full text-xs font-semibold transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">close</span> Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Messages Directory Table Card --}}
        <div class="bg-white shadow-sm border border-gray-100 overflow-hidden mb-6" style="border-radius: 20px;">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Sender</th>
                            <th class="px-6 py-4">Subject &amp; Message</th>
                            <th class="px-6 py-4">Ref Code</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Received Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($messages as $msg)
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $msg->status === 'unread' ? 'bg-amber-50/30 font-semibold' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-xl bg-[#21255E]/10 text-[#21255E] flex items-center justify-center font-extrabold text-sm uppercase shrink-0 border border-[#21255E]/20">
                                            {{ substr($msg->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-[#111827] text-[14px] leading-snug truncate">{{ $msg->name }}</p>
                                            <p class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5 truncate">
                                                <span class="material-symbols-outlined text-[14px] text-gray-400">mail</span>
                                                {{ $msg->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-xs sm:max-w-md">
                                    <p class="font-bold text-[#111827] text-xs truncate">{{ $msg->subject }}</p>
                                    <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ Str::limit($msg->message, 65) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($msg->reference_no)
                                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-[11px] font-bold border border-slate-200">
                                            {{ $msg->reference_no }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">&mdash;</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($msg->status === 'unread')
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 inline-flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Unread
                                        </span>
                                    @elseif($msg->status === 'read')
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1.5">
                                            Read
                                        </span>
                                    @elseif($msg->status === 'replied')
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-green-50 text-green-700 border border-green-200 inline-flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-[14px]">reply</span> Replied
                                        </span>
                                    @else
                                        <span class="rounded-full px-3 py-1 text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200 inline-flex items-center gap-1.5">
                                            Archived
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-medium whitespace-nowrap text-xs">
                                    {{ $msg->created_at->format('M d, Y') }}
                                    <span class="text-[11px] text-gray-400 block">{{ $msg->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('superadmin.contact-messages.show', $msg) }}"
                                            class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs"
                                            title="View Message">
                                            <span class="material-symbols-outlined text-[17px]">visibility</span>
                                        </a>

                                        <form action="{{ route('superadmin.contact-messages.destroy', $msg) }}" method="POST"
                                            class="inline"
                                            data-confirm="Are you sure you want to delete this contact message from {{ $msg->name }}? This action cannot be undone."
                                            data-confirm-title="Delete Message" data-confirm-btn="Yes, Delete">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="w-8 h-8 rounded-full bg-gray-50 border border-gray-200 hover:bg-red-50 hover:text-red-700 hover:border-red-200 flex items-center justify-center transition-colors text-gray-600 shadow-2xs cursor-pointer"
                                                title="Delete Message">
                                                <span class="material-symbols-outlined text-[17px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                        <span class="material-symbols-outlined text-[32px]">inbox</span>
                                    </div>
                                    <p class="font-bold text-gray-800 text-base">No contact messages found</p>
                                    <p class="text-xs text-gray-400 mt-1">There are no inquiries matching your active filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
