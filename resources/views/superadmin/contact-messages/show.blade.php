@extends('layouts.admin')

@section('title', 'View Message — ' . $contactMessage->subject)

@section('content')
<div class="max-w-[1200px] mx-auto">

    {{-- Breadcrumb & Top Bar --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('superadmin.contact-messages.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-[#21255E] mb-3 transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Contact Inbox
            </a>
            <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 36px;">Message Details</h2>
        </div>

        <div class="flex items-center gap-3">
            {{-- Status Selector Form --}}
            <form action="{{ route('superadmin.contact-messages.update-status', $contactMessage) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2.5 rounded-full border border-gray-200 text-xs font-bold bg-white text-gray-700 outline-none focus:ring-2 focus:ring-[#21255e]/10 shadow-2xs transition-all">
                    <option value="unread" {{ $contactMessage->status === 'unread' ? 'selected' : '' }}>Mark Unread</option>
                    <option value="read" {{ $contactMessage->status === 'read' ? 'selected' : '' }}>Mark Read</option>
                    <option value="replied" {{ $contactMessage->status === 'replied' ? 'selected' : '' }}>Mark Replied</option>
                    <option value="archived" {{ $contactMessage->status === 'archived' ? 'selected' : '' }}>Mark Archived</option>
                </select>
            </form>

            <a href="{{ route('superadmin.contact-messages.reply', $contactMessage) }}"
                class="text-white px-5 py-2.5 rounded-full text-xs font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-md active:scale-95"
                style="background-color: #D7B56D;">
                <span class="material-symbols-outlined text-[18px]">reply</span>
                Reply via Email
            </a>
        </div>
    </div>

    {{-- Main Message Card --}}
    <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6" style="border-radius: 20px;">

        {{-- Sender Overview --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#21255E]/10 text-[#21255E] flex items-center justify-center font-extrabold text-xl uppercase shrink-0 border border-[#21255E]/20">
                    {{ substr($contactMessage->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-[#111827] tracking-tight">{{ $contactMessage->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1 flex flex-wrap items-center gap-4">
                        <span class="flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px] text-gray-400">mail</span>
                            {{ $contactMessage->email }}
                        </span>
                        @if($contactMessage->phone)
                            <span class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-gray-400">call</span>
                                {{ $contactMessage->phone }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="text-right text-xs text-gray-500 space-y-1">
                <p class="flex items-center justify-end gap-1 font-medium">
                    <span class="material-symbols-outlined text-[16px] text-gray-400">schedule</span>
                    {{ $contactMessage->created_at->format('F d, Y \a\t h:i A') }}
                </p>
                @if($contactMessage->ip_address)
                    <p class="text-[11px] text-gray-400 font-mono">IP: {{ $contactMessage->ip_address }}</p>
                @endif
            </div>
        </div>

        {{-- Subject & Meta Pills --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold flex items-center gap-1.5 border border-blue-200/60">
                    <span class="material-symbols-outlined text-[16px]">subject</span>
                    Subject: {{ $contactMessage->subject }}
                </span>
            </div>
            @if($contactMessage->reference_no)
                <span class="px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-mono font-bold border border-slate-200">
                    Ref Code: {{ $contactMessage->reference_no }}
                </span>
            @endif
        </div>

        {{-- Message Body --}}
        <div class="bg-gray-50/80 rounded-2xl p-6 border border-gray-200/80 text-gray-800 text-sm leading-relaxed whitespace-pre-line font-medium">
            {{ $contactMessage->message }}
        </div>

        {{-- Footer Actions --}}
        <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
            <form action="{{ route('superadmin.contact-messages.destroy', $contactMessage) }}" method="POST"
                data-confirm="Are you sure you want to delete this contact message from {{ $contactMessage->name }} permanently? This action cannot be undone."
                data-confirm-title="Delete Message" data-confirm-btn="Yes, Delete">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2.5 rounded-full text-xs font-bold text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    Delete Message
                </button>
            </form>

            <a href="{{ route('superadmin.contact-messages.reply', $contactMessage) }}"
                class="text-white font-bold text-xs px-7 py-2.5 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95"
                style="background-color: #D7B56D;">
                <span class="material-symbols-outlined text-[18px]">send</span>
                Send Direct Email Reply
            </a>
        </div>

    </div>

</div>
@endsection
