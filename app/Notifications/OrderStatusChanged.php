<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusChanged extends Notification
{
    use Queueable;
    
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
        $statuses = [
            'new' => 'Принят в работу',
            'processing' => 'Ваш букет уже собирается флористом',
            'delivering' => 'Передан курьеру и скоро будет у вас',
            'completed' => 'Доставлен и радует получателя!',
            'cancelled' => 'Отменен',
        ];

        $statusText = $statuses[$this->order->status] ?? $this->order->status;

        return (new MailMessage)
            ->subject('Обновление статуса заказа №' . $this->order->id)
            ->greeting('Здравствуйте, ' . $this->order->customer_name . '!')
            ->line('Статус вашего заказа изменился на: **' . $statusText . '**.')
            ->action('Посмотреть детали заказа', url('/my-orders'))
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
