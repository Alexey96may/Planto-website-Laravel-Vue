<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserWelcome extends Notification
{
    use Queueable;

    protected $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($password)
    {
        $this->password = $password;
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
    public function toMail($notifiable)
        {
            return (new MailMessage)
                ->subject('Ваш аккаунт в FlowerShop создан!')
                ->greeting('Здравствуйте, ' . $notifiable->name . '!')
                ->line('Вы успешно оформили заказ и мы создали для вас личный кабинет.')
                ->line('**Логин (Email):** ' . $notifiable->email)
                ->line('Ваш пароль для входа: **' . $this->password . '**')
                ->action('Войти в кабинет', url('/login'))
                ->line('Спасибо, что выбрали наш магазин!');
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
