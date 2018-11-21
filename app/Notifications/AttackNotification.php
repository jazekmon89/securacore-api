<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AttackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $attack_detail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
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
        // dump('notification', $notifiable);
        // dump('$this->attack_detail', $this->attack_detail);
        
        return (new MailMessage)
            ->subject('Client has been attacked with ' . $this->attack_detail['attack_type'])
            ->greeting('Alert!')
            ->line('A client has been attacked!' . $this->attack_detail['attack_message'] )
            ->line(' URL: ' . $this->attack_detail['url'])
            ->line('Public key: ' . $this->attack_detail['public_key'])
            ->action('Activate Security', url('/'))
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
