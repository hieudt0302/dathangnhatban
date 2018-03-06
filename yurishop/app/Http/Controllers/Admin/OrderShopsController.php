<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderShop;
use App\Models\OrderDetail;
use App\Models\Shop;
use DB;
use Validator;
use Excel;
use Carbon\Carbon;

class OrderShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        return $this->filter($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ordershops.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get ordershop by id
        $ordershop = OrderShop::where('id', $id)->first();

       
        if (empty($ordershop)) {
            return redirect()->back()
            ->with('message', 'Không tìm thấy đơn đặt hàng theo cửa hàng mã: '. $id)
            ->with('status', 'danger');
        }
       
        //get all product of ordershops by id where orderdetails.ordershop_id equal id
        $orderdetails = OrderDetail::where('ordershop_id', $id)->get();
        return view('admin.ordershops.show', compact('ordershop', 'orderdetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $formatter = new NumberFormatter('vi_VN', NumberFormatter::CURRENCY);
        // var_dump($formatter->parseCurrency($request->input('freight1'), $curr)); die;
        
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|numeric|min:1|max:4',
            'freight1' => 'nullable|numeric|min:0',
            'freight2' => 'nullable|numeric|min:0',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
            ->with('message', 'Error: Lỗi không thể cập nhật, dữ liệu cung cấp không phù hợp')
            ->with('status', 'danger');
        }


        $landingcode = $request->input('landingcode');
        $status = $request->input('status');
        $freight1 = $request->input('freight1');
        $freight2 = $request->input('freight2');
        $note = $request->input('note');
    
         $orderShop = OrderShop::find($id);
        
        if (!$orderShop) {
            return redirect()->back()
            ->with('message', 'Không tìm thấy đơn đặt hàng theo cửa hàng mã: '. $id)
            ->with('status', 'danger');
        }

        if (!empty($landingcode)) {
            $orderShop->landingcode= $landingcode;
        }

        if (!empty($status)) {
            $orderShop->status= $status;
        }

        if (!empty($freight1)) {
            $orderShop->freight1= $freight1;
        }

        if (!empty($freight2)) {
            $orderShop->freight2= $freight2;
        }
        
        if (!empty($note)) {
            $orderShop->note= $note;
        }
        
        $orderShop->save();

      
        return redirect()->back()
        ->with('message', 'Lưu thay đổi thành công!')
        ->with('status', 'success');
    }

    public function setAvailable(Request $request, $id)
    {
        $orderdetail = OrderDetail::find($id);

        if (!empty($orderdetail)) {
           // $orderdetail->is_available = (int)$request->is_available;
           // Lỗi trên server: không nhận được request này???

           //fix
            if ($orderdetail->is_available>=1) {
                $orderdetail->is_available = 0;
            } else {
                $orderdetail->is_available = 1;
            }

            $orderdetail->save();

            session()->flash('success_message', 'Đánh dấu số lượng thành công!');
            return response()->json(['success' => true]);
        } else {
            session()->flash('success_message', 'Không tìm thấy sản phẩm!');
            return response()->json(['success' => false]);
        }
    }
    public function setStatus(Request $request, $id)
    {
        $orderShop = OrderShop::find($id);

        if (empty($orderShop)) {
            session()->flash('error_message', 'Không tìm thấy đơn hàng trong hệ thống!');
            return response()->json(['success' => false]);
        }

        $orderShop->status = 4;
        $orderShop->save();
        
        session()->flash('success_message', 'Chuyển trạng thái đơn hàng thành công!');
        return response()->json(['success' => true]);
    }

    public function ajustShopName(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
            ->with('message', 'Yêu cầu nhập tên cửa hàng!')
            ->with('status', 'danger');
        }

        $name = $request->input('name');

        $orderShop = OrderShop::find($id);

        if (empty($orderShop)) {
            return redirect()->back()
            ->with('message', 'Không tìm thấy đơn hàng ID ['. $id .'] trong hệ thống!')
            ->with('status', 'danger');
        }

        
        
        $shop = Shop::where('name', $name)->first();

        if (!empty($shop)) {
            //use exists
            $orderShop->shop_id =  $shop->id;
            $orderShop->save();
        } else {
            //create new
            $newShop = new Shop();
            $newShop->name = $name;
            $newShop->save();

            //use new
            $orderShop->shop_id =  $newShop->id;
            $orderShop->save();
        }

        return redirect()->back()
        ->with('message', 'Cập nhật tên cửa hàng thành công!')
        ->with('status', 'success');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderShop =  OrderShop::find($id);

        if (empty($orderShop)) {
            return redirect()->back()
            ->with('message', 'Đơn hàng không tồn tại trong hệ thống, vui lòng kiểm tra lại!')
            ->with('status', 'danger');
        }

        ///TODO: Remove ordershop_id from orderdetail
        $orderDetails = OrderDetail::where('ordershop_id', $orderShop->id)->get();

        foreach ($orderDetails as $item) {
            $item->ordershop_id = null;
            $item->save();
        }

        $orderShop->delete();

        return redirect()->back()
                        ->with('message', 'Bạn vừa xóa vĩnh viễn một đơn hàng!')
                        ->with('status', 'success');
    }
    
    /**
     * Find the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        return $this->filter($request);
    }

    public function filter(Request $request)
    {
        //Get input values
        $shopname = $request->input('shopname');
        $landingcode = $request->input('landingcode');
        $status = $request->get('status');
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');

      
        //Ajust status
        if (empty($status) || (int)$status===0) {
            $statusIn =[1,2,3,4];
        } else {
            $statusIn=[$status];
        }
        

        $query  =DB::table('ordershops as O')->orderby('O.id', 'DESC')
                        ->join('shops as S', 'S.id', '=', 'O.shop_id')
                        ->select('O.id', 'O.status', 'O.landingcode', 'O.freight1', 'O.freight2', 'O.totalamount', 'O.created_at', 'S.name')
                        ->whereIn('O.status', $statusIn);

        if (!empty($fromDate)) {
            $from = date('Y-m-d'.' 00:00:00', strtotime($fromDate));
            $query->where('O.created_at', '>=', $from);
        }

        if (!empty($toDate)) {
            $to = date('Y-m-d'.' 23:59:59', strtotime($toDate));
            $query->where('O.created_at', '<=', $to);
        }

        if (!empty($landingcode)) {
            $query->where('O.landingcode', 'LIKE', '%' . $landingcode . '%');
        }

        if (!empty($shopname)) {
            $query->where('S.name', 'LIKE', '%' . $shopname . '%');
        }

        $ordershops = $query->paginate(20);

        return view('admin.ordershops.index', compact('ordershops', 'shopname', 'landingcode', 'status', 'fromDate', 'toDate'))
             ->with('i', ($request->input('page', 1) - 1) * 20);
    }

    public function export($id, $type)
    {
        $ordershop = OrderShop::where('id', $id)->first();

        if (empty($ordershop)) {
        }

        $query = DB::table('ordershops as OS')
        ->join('orderdetails as D', 'D.ordershop_id', '=', 'OS.id')
        ->select('D.url', 'D.productname', 'D.size', 'D.color', 'D.unitprice', 'D.quantity', 'D.total', 'OS.freight1', 'OS.freight2', 'OS.totalamount')
        ->where('OS.id', $ordershop->id);

       
        if ($ordershop->status===1) {
            $status = "Chờ xử lý";
        } elseif ($ordershop->status===2) {
            $status = "Đang xử lý";
        } elseif ($ordershop->status===3) {
            $status = "Hoàn thành";
        } elseif ($ordershop->status===4) {
            $status = "Hủy";
        } else {
            $status = "Không xác định";
        }

        $data = array(
            array('Cửa Hàng:', $ordershop->shop->name),
            array('Mã Vận Đơn:', $ordershop->landingcode),
            array('Ngày Đặt Hàng:', $ordershop->orderdate),
            array('Ngày Giao Hàng:', $ordershop->shippeddate),
            array('Trạng Thái:', $status),
            array(),
            array('STT','Tên Sản Phẩm', 'Kích Cỡ', 'Màu Sắc','Đơn Giá', 'Số Lượng','Tổng Tiền', 'Url'),
        );

        $i = 0;
        foreach ($query->get() as $item) {
            $i++;
            $data[] = array($i, $item->productname, $item->size, $item->color,number_format($item->unitprice, 2, ',', '.'), $item->quantity,number_format($item->total, 2, ',', '.'),$item->url);
        }

        $data[] = array();
        $data[] = array('Tổng Giá Sản Phẩm:', number_format($ordershop->totalamount, 2, ',', '.') . ' Tệ');
        $data[] = array('Phí Vận Chuyển 1:', number_format($ordershop->freight1, 2, ',', '.') . ' Tệ');
        $data[] = array('Phí Vận Chuyển 2:', number_format($ordershop->freight2, 2, ',', '.') . ' Tệ');
        $data[] = array('Thành Tiền:', number_format($ordershop->getFinalPrice(), 2, ',', '.') . ' Tệ');

        $exportFileName ="DonHang-". Carbon::now()->subDay()->format('Ymdhis');

        return Excel::create($exportFileName, function ($excel) use ($data) {
            $excel->sheet('OrderDetail', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);

                $range = "A7:H" . (count($data)-5);
                $sheet->setBorder($range, 'thin');

                $sheet->cells('A1:A5', function ($cells) {
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('A7:H7', function ($cells) {
                    $cells->setFontWeight('bold');
                });
            });
        })->download($type); //xls, csv
    }
}
