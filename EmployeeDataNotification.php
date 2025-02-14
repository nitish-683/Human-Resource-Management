<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeDataNotification extends Notification
{
    use Queueable;

    private $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        ->line('Please check the Employee credentials.')
        ->line('**Joining Date:** ' . date('d-m-Y',strtotime($this->user['joining_date'])))
        ->line('**Employee Code:** ' . $this->user['employee_code'])
        ->line('**Email ID:** ' . $this->user['email'])
        ->line('**Password:** ' . "Same as your candidate password") // Use plain password here
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
