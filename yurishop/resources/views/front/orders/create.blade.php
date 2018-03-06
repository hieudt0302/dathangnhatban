@extends('layouts.master') @section('title','Tạo Đơn Hàng') @section('content')
<br>
<div class="container">
    <div class="row">
        <h1 style="color:#d9534f;">
            <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Đặt Hàng
        </h1>
        <h4>Điền vào các mục dưới đây để hoàn thành đơn đặt hàng của bạn !</h4>
        @include('notifications.status_message') 
		@include('notifications.errors_message')
    </div>
    <br>
    <div class="row">
        <div class="col-xs-4">
            <div class="bs-example bs-order-ship">
                <form>
                    <div class="form-group">
                        <label for="bookaddress">Chọn từ sổ địa chỉ</label>
                        @if (sizeof($bookaddress) <= 0)
                        <select id="bookaddress" disabled name="bookaddress" type="text" class="form-control">
                            <option value="0">----- Bạn chưa thêm địa chỉ nào trong sổ -----</option>
                        </select>
                        @else
                        <select id="bookaddress" name="bookaddress" type="text" class="form-control">
                            <option value="0">----- Vui lòng chọn một địa chỉ -----</option>
                            @foreach ($bookaddress as $key => $add)
                            <option value="{{ $add->id }}">{{ $add->last_name }} {{ $add->first_name }} - {{ $add->address }}, {{ $add->district }}, {{ $add->city }} - {{ $add->phone }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="checkbox checkbox-warning">
                        <input id="changeaddress" type="checkbox">
                        <label for="changeaddress">Sử dụng địa chỉ khác</label>
                    </div>
                    <div class="collapse">
                        <div class="form-group">
                            <label for="address">Địa Chỉ (*)</label>
                            <input type="text" class="form-control" id="address" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="district">Quận/Huyện (*)</label>
                            <input type="text" class="form-control" id="district" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="city">Tỉnh/Thành Phố (*)</label>
                            <input type="text" class="form-control" id="city" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Số Điện Thoại (*)</label>
                            <input type="text" class="form-control" id="phone" placeholder="">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-xs-8">
            <div class="bs-example bs-order-check">
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-left">Tên Sản Phẩm</th>
                                <th class="text-right">Kích Cỡ</th>
                                <th class="text-right">Màu</th>
                                <th class="text-right">Số Lượng</th>
                                <th class="text-right last">Giá Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (Cart::content() as $item)
                            <tr>
                                <td class="text-left">{{ $item->name }}</td>
                                <td>{{$item->size}}</td>
                                <td>
                                    @if($item->color_img != '')
                                    <img src="{{ $item->color_img }}" style="width: 40px">
                                    @else
                                        {{ $item->color }}
                                    @endif
                                </td>
                                <td class="text-right">{{ $item->quantity }}</td>
                                <td class="text-right last cny-money" ><p>{{ $item->total_price }}</p></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>

                            <tr>
                                <td style="" class="text-right" colspan="4">
                                    Thành tiền </td>
                                <td  class="text-right  cny-money">
                                    <p class="price">{{ Cart::totalMoney() }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="" class="text-right" colspan="4">
                                    <p>Tỷ giá</p>
                                </td>
                                <td style="" class="text-right  vnd-money">
                                    <p class="price">{{ number_format($rate, 2, ',', '.') }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="" class="text-right" colspan="4">
                                    <strong>Tổng Số Tiền (tạm tính)</strong>
                                </td>
                                <td style="" class="text-right last">
                                    <strong class="vnd-money"><p class="price">{{ number_format($final, 2, ',', '.') }}</p></strong>
                                </td>
                            </tr>

                        </tfoot>
                    </table>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label for="note">Ghi Chú</label>
                            <input id="note" type="text" class="form-control" placeholder="Ghi chú khác...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <form action="{{ url('/order/create') }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" id="use_bookaddress" name="use_bookaddress" value="true">
                        <input type="hidden" id="bookaddress_id" name="bookaddress_id" value="0">
                        <input type="hidden" id="shipaddress" name="shipaddress">
                        <input type="hidden" id="shipdistrict" name="shipdistrict">
                        <input type="hidden" id="shipcity" name="shipcity">
                        <input type="hidden" id="shipphone" name="shipphone">
                        <input type="hidden" id="ordernote" name="ordernote">
                         <button type="submit" class="btn-lg btn-primary">
                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Gửi Đơn Hàng
                        </button>
                    </form>
                    <h6 style="color:#d9534f;">
                        Lưu ý: Chúng tôi sẽ kiểm tra đơn hàng và liên hệ để xác nhận với bạn!
                    </h6>
                </div>
            </div>

        </div>
    </div>
</div>
<br> @endsection @section('scripts')
<script>
    $(document).ready(function() {

        $("#changeaddress").click(function() {
            $('#bookaddress').attr("disabled", $(this).is(":checked"));
            $(".collapse").collapse('toggle');
            if($(this).is(":checked")){
                $('#use_bookaddress').val('false');
            }
            else{
                $('#use_bookaddress').val('true');
            }
        });

        $('#bookaddress').on('change', function() {
            var id = this.value;
            $('#bookaddress_id').val(id);
        });

        $('#address').on('input', function() {
            var address = this.value;
            $('#shipaddress').val(address);
        });

        $('#district').on('input', function() {
            var district = this.value;
            $('#shipdistrict').val(district);
        });

        $('#city').on('input', function() {
            var city = this.value;
            $('#shipcity').val(city);
        });

        $('#phone').on('input', function() {
            var phone = this.value;
            $('#shipphone').val(phone);
        });

        $('#note').on('input', function() {
            var note = this.value;
            $('#ordernote').val(note);
        });
      
    });
</script>
@endsection