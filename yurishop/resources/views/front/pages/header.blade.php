<div class="top-header" id="topHead">
    <div class="row top-header-container">
        <div class="container">
            <a href="{{url('/')}}" class="logo-top"></a>
            @if(!Auth::guest())
            <div class="cart-block">                                
                <span class="order-label">
                        <a href="{{ url('/cart') }}" id="cart-item-count">
                            <i class="fa fa-shopping-cart shopping-cart-icon" aria-hidden="true"></i>
                            Giỏ hàng  ({{ Cart::totalProduct()}})
                        </a>
                </span> 
                <span class="list-label">
                        <a href="{{ url('/order') }}">
                            <i class="fa fa-list-alt" aria-hidden="true"></i>
                            Danh sách đơn hàng
                        </a>
                </span> 
            </div>
            @endif
            <div class="user-block">
                @if (Auth::guest())
                <a href="{{ url('/register') }}" id="link-modal-sign-up" data-reveal-id="modal-signup" class="modal-signup nb-signup">      
						Đăng ký
                    </a> |
                <a href="{{ url('/login') }}" id="link-modal-login" data-reveal-id="modal-signup2" class="modal-signup2 nb-signup">
                        @lang('auth.login')
            </a> @else
                <div>
                    <li class="dropdown hide-point">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->last_name }} {{ Auth::user()->first_name }}<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ url('/profile') }}"><i class="fa fa-fw fa-user"></i> Tài Khoản</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                <i class="fa fa-fw fa-power-off"></i> Thoát
                            </a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>