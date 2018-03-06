<?php
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
 
class OrderResponsedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $orderID;
    protected $username;
 
    /**
     * Create a new notification instance.
     *
     * Send Order Status constructor.
     * @param $orderID
     * @param $status
     */
    public function __construct($orderID, $username)
    {
        $this->orderID = $orderID;
        $this->username = $username;

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
                    //->from('ducthang.23788@gmail.com')
                    //->to('thangld@jcs-corp.com')
                    ->subject('Taobao Đà Nẵng - Phản hồi đơn hàng')
                    ->greeting('Xin chào, '.$notifiable->first_name)
                    ->line($this->username. ' đã phản hồi trong đơn hàng.')
                    ->action('Xem Đơn Hàng', route('front.orders.show', ['id'=>$this->orderID]));
                    //->line('Cám ơn bạn đã sử dụng dịch vụ của chúng tôi !');
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
