<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCandidateNotification extends Notification
{
    use Queueable;

    private $candidate;
    private $plainPassword; // Store the plain-text password
    /**
     * Create a new notification instance.
     */
    public function __construct($candidate, $plainPassword)
    {
        $this->candidate = $candidate;
        $this->plainPassword = $plainPassword; // Store it
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
        ->greeting('Hello ' . $this->candidate['name'] . ',')
        ->line('Please login using the link and credentials provided below to complete your documentation.')
        ->line('**Login Link:** [' . url('/candidate/login') . '](' . url('/candidate/login') . ')')
        ->line('**Username:** ' . $this->candidate['email'])
        ->line('**Password:** ' . $this->plainPassword) // Use plain password here
        ->line('If you have any questions, feel free to reach out.')
        ->line('Thank you for using our application!')
        ->salutation('Best Regards,')
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
