<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeePolicyResponse extends Notification
{
    use Queueable;

    private $user;
    private $policy;

    private $candidate;

    /**
     * Create a new notification instance.
     */
    public function __construct($user,$policy,$candidate)
    {
        $this->user = $user;
        $this->policy = $policy;
        $this->candidate = $candidate;

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
        ->line('Candidate '.$this->candidate['name'].' with the Employee code '.$this->candidate['employee_code'].' submitted the response of Company\'s Policy Questionnaires.')
        ->line('Please check the Employee response of Company\'s Policy Questionnaires:-')
        ->line('**Handbook Received:** ' . $this->policy['handbook_received'])
        ->line('**Handbook Purpose:** ' . $this->policy['handbook_purpose'])
        ->line('**Policy Clarity:** ' . $this->policy['policy_clarity'])
        ->line('**Harassment Policy:** ' . $this->policy['harassment_policy'])
        ->line('**Violation Steps:** ' . $this->policy['violation_steps'])
        ->line('**Leave Policy:** ' . $this->policy['leave_policy'])
        ->line('**Formal Day:** ' . $this->policy['formal_day'])
        ->line('**Casual Leaves:** ' . $this->policy['casual_leaves'])
        ->line('**Policies Fair:** ' . $this->policy['policies_fair'])
        ->line('**Policy Update:** ' . $this->policy['policy_update'])
        ->line('**Handbook Help:** ' . $this->policy['handbook_help'])
        ->line('**Handbook Help Details:** ' . $this->policy['handbook_help_details'])
        ->line('**Accessibility Suggestions:** ' . $this->policy['accessibility_suggestions']);
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
