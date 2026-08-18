@extends('layouts.admin')

@section('title', 'Compose Reply — ' . $contactMessage->name)

@section('content')
<div class="max-w-[1200px] mx-auto">

    <!-- Page Title & Actions Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="{{ route('superadmin.contact-messages.show', $contactMessage) }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-[#21255E] mb-3 transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Message Details
            </a>
            <h2 class="text-4xl font-extrabold text-[#111827] tracking-tight leading-none" style="font-size: 36px;">Compose Email Reply</h2>
            <p class="text-gray-500 text-sm mt-2 font-medium">Draft and send an official institutional reply email to {{ $contactMessage->name }}.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.contact-messages.show', $contactMessage) }}"
                class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-full text-xs font-bold hover:bg-gray-50 transition-all shadow-2xs">
                Cancel
            </a>
        </div>
    </div>

    <!-- Original Inquiry Banner -->
    <div class="bg-blue-50/80 border border-blue-200/80 p-5 mb-6 space-y-2" style="border-radius: 16px;">
        <div class="flex items-center justify-between flex-wrap gap-2 text-xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600 text-[20px]">mark_email_unread</span>
                <span class="font-bold text-blue-950">Original Message from {{ $contactMessage->name }}</span>
                <span class="text-blue-700 font-mono">({{ $contactMessage->email }})</span>
            </div>
            <span class="text-blue-600 text-[11px] font-semibold">Received {{ $contactMessage->created_at->format('M d, Y \a\t h:i A') }}</span>
        </div>
        @if($contactMessage->message)
            <p class="text-xs text-blue-900/80 italic line-clamp-2 pl-7">"{{ Str::limit($contactMessage->message, 180) }}"</p>
        @endif
    </div>

    <!-- Compose Email Form Card -->
    <div class="bg-white shadow-sm border border-gray-100 p-6 sm:p-8 space-y-6" style="border-radius: 20px;">
        <form action="{{ route('superadmin.contact-messages.send-reply', $contactMessage) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Recipient Badge -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-gray-50 border border-gray-200/80">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#21255E] text-white flex items-center justify-center font-extrabold text-sm uppercase shrink-0">
                        {{ substr($contactMessage->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Recipient To</p>
                        <p class="text-sm font-bold text-[#111827] mt-0.5">{{ $contactMessage->name }} &lt;{{ $contactMessage->email }}&gt;</p>
                    </div>
                </div>

                @if($contactMessage->reference_no)
                    <span class="px-3.5 py-1.5 rounded-full bg-slate-100 text-slate-700 text-xs font-mono font-bold border border-slate-200">
                        Ref Code: {{ $contactMessage->reference_no }}
                    </span>
                @endif
            </div>

            <!-- Subject Input -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Subject *</label>
                <input type="text" name="subject" value="{{ old('subject', 'RE: ' . $contactMessage->subject) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-semibold text-gray-800 bg-gray-50/50 transition-all">
                @error('subject')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Message Body Textarea -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Reply Email Message Body *</label>
                <textarea name="message" rows="9" required
                    placeholder="Type your official reply message here..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-[#21255e]/10 outline-none text-sm font-medium text-gray-800 bg-gray-50/50 resize-y transition-all">{{ old('message', "Dear {$contactMessage->name},\n\nThank you for reaching out to RAZA UL ULOOM ISLAMIA HSS.\n\n\n\nBest regards,\nAdministration Team\nRAZA UL ULOOM ISLAMIA HSS — POONCH") }}</textarea>
                @error('message')
                <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>@enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-2 border-t border-gray-100">
                <a href="{{ route('superadmin.contact-messages.show', $contactMessage) }}"
                    class="px-6 py-3 rounded-full border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-all shadow-2xs">
                    Cancel
                </a>
                <button type="submit"
                    class="text-white font-bold text-xs px-8 py-3 rounded-full transition-all shadow-md flex items-center gap-2 active:scale-95 cursor-pointer"
                    style="background-color: #D7B56D;">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <span>Send Reply Email</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
