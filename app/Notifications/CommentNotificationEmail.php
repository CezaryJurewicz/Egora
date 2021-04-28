<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\CommentNotification;

class CommentNotificationEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CommentNotification $notification)
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
        if ($this->notification->comment->is_response()) {
            $action = route('ideas.view', [$this->notification->comment->commentable->commentable, 'open'=>$this->notification->comment->commentable->id]).'#comment-'.$this->notification->comment->id;
        } else {
            $action = route('ideas.view', [$this->notification->comment->commentable]).'#comment-'.$this->notification->comment->id;            
        }
        
        return (new MailMessage)
                    ->subject($this->notification->sender->active_search_names. $this->notification->message)
                    ->greeting('Philosopher '.($this->notification->receiver->name).' – ')
                    ->line($this->notification->sender->active_search_name.$this->notification->message)
                    ->action('Comment', $action)
                    ->line('Egora, "The Worldwide Stock-Market of Ideas"');
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
