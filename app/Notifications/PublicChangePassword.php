<?php

namespace App\Notifications;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PublicChangePassword extends Notification
{
    use Queueable;

    public $website;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
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
                    ->subject('SecuraCore Change Password')
                    ->greeting('Hey ' . $notifiable->first_name . ' ' . $notifiable->last_name . ',')
                    ->line('You have successfully changed your password. You may now login with your new password.')
                    ->action('Login to Dashboard', $this->website->url . '/securacore/pages/install/index.php');
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
