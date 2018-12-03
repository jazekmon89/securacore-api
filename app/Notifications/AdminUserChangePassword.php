<?php

namespace App\Notifications;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminUserChangePassword extends Notification implements ShouldQueue
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
                    ->line('Your password has been changed by our representative, as requested.')
                    ->line('You may now login with your new system generated password.')
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
