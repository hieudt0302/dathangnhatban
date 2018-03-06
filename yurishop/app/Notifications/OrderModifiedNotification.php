<?php
namespace App\Notifications;
 
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
 
class OrderModifiedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $orderID;
    protected $admin_username;
    protected $operation;
    protected $attribute;
    protected $product_name;
 
    /**
     * Create a new notification instance.
     *
     * Send Order Status constructor.
     * @param $orderID
     * @param $status
     */
    public function __construct($orderID, $admin_username, $operation, $attribute, $product_name)
    {
        $this->orderID = $orderID;
        $this->admin_username = $admin_username;
        $this->product_name = ' sản phẩm '.$product_name;

        if ($operation==='edit') {
            $this->operation = " đã thay đổi";
        } elseif ($operation==='delete') {
            $this->operation = " đã xóa";
        } else {
            $this->operation = "";
        }

        if ($attribute=='size') {
            $this->attribute = ' kích thước của';
        }
        elseif ($attribute=='color') {
            $this->attribute = ' màu sắc của';
        }
        elseif ($attribute=='quantity') {
            $this->attribute = ' số lượng của';
        }
        elseif ($attribute=='unit_price') {
            $this->attribute = ' giá của';
        }
        else{
            $this->attribute = '';
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
                    ->subject('Taobao Đà Nẵng - Điều chỉnh đơn hàng')
                    ->greeting('Xin chào, '.$notifiable->first_name)
                    ->line($this->admin_username. $this->operation. $this->attribute. $this->product_name. ' trong đơn hàng của bạn.')
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
