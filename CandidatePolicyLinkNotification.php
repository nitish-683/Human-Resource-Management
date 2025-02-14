<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidatePolicyLinkNotification extends Notification
{
    use Queueable;

    private $user;
    private $token; // Store the plain-text password
    /**
     * Create a new notification instance.
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token; // Store it
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->greeting('Hello ' . $this->user['name'] . ',')
        ->line('Please open this link and fill the form.')
        ->line('**Visit this Link:** [' . url('/policy-form')."?token=".$this->token . '](' . url('/policy-form')."?token=".$this->token . ')')
        ->line('If you have any questions, feel free to reach out.')
        ->line('Thank you for using our application!')
        ->line('The HR Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
