<?php

namespace App\Notifications;

use App\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminUserAndWebsiteRegistrationNotification extends Notification
{
    use Queueable;

    public $website;

    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Website $website, $password)
    {
        $this->website = $website;
        $this->password = $password;
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
        $activation_url = $this->website->url;
        $last_character = substr($activation_url, strlen($activation_url)-1);
        if (strlen($activation_url) > 0 && $last_character == '/' || $last_character == '\\') {
            $activation_url = substr($activation_url, 0, strlen($activation_url)-1);
        }
        $activation_url .= '/securacore-client/pages/install/index.php';
        return (new MailMessage)
                    ->subject('SecuraCore Registration')
                    ->greeting('Welcome, ' . $notifiable->first_name . ' ' . $notifiable->last_name . ', to SecuraCore.')
                    ->line('You are now registered to SecuraCore. Our system has generated a password for you.')
                    ->line('Below is your login credentials and public key:')
                    ->line('Username/Email: ' . $notifiable->email)
                    ->line('Password: ' . $this->password)
                    ->line("Public key: " . $this->website->public_key)
                    ->line('Please activate your public key here: ')
                    ->action('Activate Public Key', $activation_url);
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
