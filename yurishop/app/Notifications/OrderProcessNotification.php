<?php
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
 
class OrderProcessNotification extends Notification implements ShouldQueue
{
    use Queueable;
 
    protected $orderID;
    protected $status;
 
    /**
     * Create a new notification instance.
     *
     * Send Order Status constructor.
     * @param $orderID
     * @param $status
     */
    public function __construct($orderID, $status)
    {
        $this->orderID = $orderID;
        
        if ($status===3) {
            $this->status = "đã được xác nhận, xin hãy đặt cọc trước khi nhận hàng.";
        } elseif ($status===4) {
            $this->status = "đã được đặt cọc.";
        } 
        elseif ($status===7) {
            $this->status = "đã được hủy.";
        }
        else {
            $this->status = "có trạng thái không xác định, vui lòng liên hệ quản trị viên";
        }
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
                    ->subject('Tình Trạng Đơn Hàng')
                    ->greeting('Xin chào, '.$notifiable->first_name)
                    ->line('Đơn hàng của bạn '. $this->status)
                    ->action('Xem Đơn Hàng', route('front.orders.show', ['id'=>$this->orderID]))
                    ->line('Cám ơn bạn đã sử dụng dịch vụ của chúng tôi !');
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
