<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\TeamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly TeamInvitation $invitation,
    ) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $orgName = $this->invitation->organization->name;
        $inviterName = $this->invitation->inviter->name;
        $acceptUrl = url("/invitations/{$this->invitation->token}/accept");

        return (new MailMessage())
            ->subject("You're invited to join {$orgName} on VisionFlow")
            ->greeting("Hello!")
            ->line("{$inviterName} has invited you to join **{$orgName}** as a **{$this->invitation->role->value}**.")
            ->when($this->invitation->personal_message, function (MailMessage $message) {
                $message->line("Personal message: \"{$this->invitation->personal_message}\"");
            })
            ->action('Accept Invitation', $acceptUrl)
            ->line("This invitation expires on {$this->invitation->expires_at->format('M d, Y')}.");
    }
}
