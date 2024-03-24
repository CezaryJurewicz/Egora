<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notification as NotificationModel;

class UserInvitedToIdea extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationModel $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject( $this->notification->sender->name.' invited you to examine an idea!')
                    ->greeting('Philosopher '.($this->notification->receiver->name).' – ')
                    ->line('What do you think about this idea?')
                    ->action('Idea', route('ideas.view',[$this->notification->idea->id, 'notification_id' => $this->notification->id, 'cnt']))
                    ->line('Your voice can make this idea rise or fall in Egora,')
                    ->line('"The Worldwide Stock-Market of Ideas".');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
