<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\BroadcastMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use App\Models\UserGift;

class GiftRequestedToAdmin extends Notification
{
    use Queueable;

    public $user_gift;

    public function __construct(UserGift $user_gift)
    {
        $this->user_gift = $user_gift;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast',WebPushChannel::class];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'id' => $this->user_gift->id,
                'gift' => [
                    'id' => $this->user_gift->gift->id,
                    'name' => $this->user_gift->gift->name,
                ],
                'status' => $this->user_gift->status->slug,
                'user' => [
                    'id' => $this->user_gift->user->id,
                    'name' => $this->user_gift->user->name
                ]
            ]
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->user_gift->id,
            'gift' => [
                'id' => $this->user_gift->gift->id,
                'name' => $this->user_gift->gift->name,
            ],
            'status' => $this->user_gift->status->slug,
            'user' => [
                'id' => $this->user_gift->user->id,
                'name' => $this->user_gift->user->name
            ]
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        $url = route('admin.gift-logs.index');
        $body = $this->user_gift->user->name .' ဆီမှ '.$this->user_gift->gift->name.' အား လဲလှယ်ရန် request လုပ်လာပါသည်။' ;
    
        return (new WebPushMessage)
            ->title('Requested Gift')
            ->icon('/images/icons/icon-144x144.png')
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
