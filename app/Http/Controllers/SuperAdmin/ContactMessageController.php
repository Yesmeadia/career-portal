<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ActivityLog;
use App\Mail\ContactMessageReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = ContactMessage::latest();

        if ($status && in_array($status, ['unread', 'read', 'replied', 'archived'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('reference_no', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = ContactMessage::unread()->count();

        return view('superadmin.contact-messages.index', compact('messages', 'status', 'search', 'unreadCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
        }

        return view('superadmin.contact-messages.show', compact('contactMessage'));
    }

    public function reply(ContactMessage $contactMessage)
    {
        return view('superadmin.contact-messages.reply', compact('contactMessage'));
    }

    public function sendReply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
        ]);

        try {
            if (!$contactMessage->email) {
                return redirect()->back()->with('error', 'Sender email address is missing.');
            }

            Mail::to($contactMessage->email)->send(
                new ContactMessageReplyMail(
                    $contactMessage,
                    $validated['subject'],
                    $validated['message'],
                    auth()->user()->name
                )
            );

            $contactMessage->update(['status' => 'replied']);

            ActivityLog::record(
                "Direct reply email sent by Super Admin to {$contactMessage->name} ({$contactMessage->email}): {$validated['subject']}",
                $contactMessage,
                'contact-messages'
            );

            return redirect()->route('superadmin.contact-messages.show', $contactMessage)
                ->with('success', 'Reply email sent successfully to ' . $contactMessage->email . ' and status marked as Replied.');
        } catch (\Throwable $e) {
            Log::error("Direct email reply to {$contactMessage->email} failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send email reply: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied,archived',
        ]);

        $contactMessage->update(['status' => $validated['status']]);

        return back()->with('success', 'Message status updated to ' . ucfirst($validated['status']) . '.');
    }

    public function destroy($id)
    {
        $contactMessage = ContactMessage::find($id);
        if ($contactMessage) {
            $contactMessage->delete();
            return redirect()->route('superadmin.contact-messages.index')->with('success', 'Contact message deleted successfully.');
        }

        return redirect()->route('superadmin.contact-messages.index')->with('info', 'Contact message already removed.');
    }
}
