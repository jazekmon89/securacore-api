<?php

namespace App\Notifications;

use App\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClientAttackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $attack_detail;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Log $data)
    {
        $this->attack_detail = $data;
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
            ->subject('Your site has been attacked with ' . $this->attack_detail->type)
            ->greeting('Alert!')
            ->line('Your site has been attacked with ' . $this->attack_detail->type )
            ->line(' URL: ' . $this->attack_detail->referer_url)
            ->line('Please notify your administrator now!');
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
