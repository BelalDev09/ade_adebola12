<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifyUser extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Channels
     */
    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Database
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['thankyou'] ?? null,
            'url' => $this->data['url'] ?? null,
        ];
    }

    /**
     * Broadcast
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toDatabase($notifiable),
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Mail
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->data['title'])
            ->line($this->data['thankyou'] ?? 'You have a new notification')
            ->action(
                $this->data['enrollmentText'] ?? 'View',
                $this->data['url'] ?? url('/')
            )
            ->line('Thank you for using our application!');
    }

    /**
     * Fallback array (used by broadcast sometimes)
     */
    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
