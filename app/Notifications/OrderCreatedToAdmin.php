<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\BroadcastMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use \App\Models\Order;

class OrderCreatedToAdmin extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast', WebPushChannel::class];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'status' => $this->order->status->slug,
                'order' => [
                    'id' => $this->order->id,
                    'order_no' => $this->order->order_no,
                ],
                'customer' => [
                    'name' => $this->order->customer->name,
                    'phone' => $this->order->customer->phone,
                ],
                'message' => $this->order->customer->name . 'မှ Order တင်ထားပါသည်။',
                'url' => route('admin.orders.edit', $this->order->id),
            ]
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }


    public function toArray($notifiable)
    {
        return [
            'status' => $this->order->status->slug,
            'order' => [
                'id' => $this->order->id,
                'order_no' => $this->order->order_no,
            ],
            'customer' => [
                'name' => $this->order->customer->name,
                'phone' => $this->order->customer->phone,
            ],
            'message' => $this->order->customer->name . 'မှ Order တင်ထားပါသည်။',
            'url' => route('admin.orders.edit', $this->order->id),
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $url = route('admin.orders.edit', $this->order->id);
        $body = $this->order->customer->name . ' မှ Order တင်ထားပါသည်။';

        return (new WebPushMessage)
            ->title('Recieved New Order')
            ->icon('/images/default.png')
            ->badge('/images/noti-badge.png')
            ->vibrate([100, 50, 100])
            ->body($body)
            ->action('View', 'view')
            ->data([
                'action' => 'view',
                'url' => $url
            ]);
    }
}
