<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CartItemUpdatedNotification extends Notification
{
    use Queueable;

    public $cartItem;
    public $isDeleted;

    public function __construct($cartItem, $isDeleted = false)
    {
        $this->cartItem = $cartItem;
        $this->isDeleted = $isDeleted;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        if ($this->isDeleted) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Cart Item Removed')
                ->line('One of your cart items was removed by the vendor.');
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Cart Item Updated')
            ->line('One of your cart items has been updated by the vendor.')
            ->line('Quantity: ' . $this->cartItem->quantity)
            ->line('Price: $' . number_format($this->cartItem->price, 2));
    }
}
