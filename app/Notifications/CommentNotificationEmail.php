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
        $title = 'Open';
        
        if ($this->notification->comment->commentable && $this->notification->comment->commentable->commentable instanceof \App\User) {
            $action = route('users.about', [ $this->notification->comment->commentable->commentable->active_search_names->first()->hash,'open'=>$this->notification->comment->commentable->id]).'#comment-'.$this->notification->comment->id;
        } else if ($this->notification->comment->commentable && $this->notification->comment->commentable instanceof \App\User) {
            $action = route('users.about', [ $this->notification->comment->commentable->active_search_name_hash,'open'=>$this->notification->comment->id]).'#comment-'.$this->notification->comment->id;
        } else if ($this->notification->comment->is_response()) {
            $action = route('ideas.view', [$this->notification->comment->commentable->commentable, 'comment_notification_id'=> $this->notification->id, 'open'=>$this->notification->comment->commentable->id, 'comments'=>'']).'#comment-'.$this->notification->comment->id;
        } else {
            $action = route('ideas.view', [$this->notification->comment->commentable, 'comment_notification_id'=> $this->notification->id, 'comments'=>'']).'#comment-'.$this->notification->comment->id;            
        }
        
        return (new MailMessage)
                    ->subject($this->notification->sender->name. $this->notification->message)
                    ->greeting('Philosopher '.($this->notification->receiver->name).' – ')
                    ->line($this->notification->sender->name.$this->notification->message)
                    ->action($title, $action)
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
