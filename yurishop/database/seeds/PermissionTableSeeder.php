<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [
			// ROLES
			[
        		'name' => 'role-list',
        		'display_name' => 'Xem danh sách quyền',
        		'description' => 'Xem danh sách quyền'
        	],
			[
        		'name' => 'role-show',
        		'display_name' => 'Hiển thị chi tiết quyền',
        		'description' => 'Hiển thị chi tiết quyền'
        	],
        	[
        		'name' => 'role-create',
        		'display_name' => 'Tạo mới quyền',
        		'description' => 'Tạo mới quyền'
        	],
        	[
        		'name' => 'role-edit',
        		'display_name' => 'Chỉnh sửa quyền',
        		'description' => 'Chỉnh sửa quyền'
        	],
        	[
        		'name' => 'role-delete',
        		'display_name' => 'Xóa quyền',
        		'description' => 'Xóa quyền'
			],
			// USERS
        	[
        		'name' => 'user-list',
        		'display_name' => 'Xem danh sách tài khoản  người dùng',
        		'description' => 'Xem danh sách tài khoản người dùng'
        	],
			[
        		'name' => 'user-show',
        		'display_name' => 'Hiển thị chi tiết tài khoản  người dùng',
        		'description' => 'Hiển thị chi tiết tài khoản  người dùng'
        	],
        	[
        		'name' => 'user-create',
        		'display_name' => 'Tạo mới tài khoản  người dùng',
        		'description' => 'Tạo mới tài khoản  người dùng'
        	],
        	[
        		'name' => 'user-edit',
        		'display_name' => 'Chỉnh sửa tài khoản người dùng',
        		'description' => 'Chỉnh sửa tài khoản  người dùng'
        	],
        	[
        		'name' => 'user-delete',
        		'display_name' => 'Xóa tài khoản người dùng',
        		'description' => 'Xóa tài khoản người dùng'
			],
			// ORDER
			[
        		'name' => 'order-list',
        		'display_name' => 'Xem danh sách đơn đặt hàng theo người dùng',
        		'description' => 'Xem danh sách đơn đặt hàng theo người dùng'
        	],
			[
        		'name' => 'order-show',
        		'display_name' => 'Hiển thị chi tiết đơn đặt hàng theo người dùng',
        		'description' => 'Hiển thị chi tiết đơn đặt hàng theo người dùng'
        	],
        	[
        		'name' => 'order-create',
        		'display_name' => 'Tạo mới đơn đặt hàng theo người dùng',
        		'description' => 'Tạo mới đơn đặt hàng theo người dùng'
        	],
        	[
        		'name' => 'order-edit',
        		'display_name' => 'Chỉnh sửa đơn đặt hàng theo người dùng',
        		'description' => 'Chỉnh sửa đơn đặt hàng theo người dùng'
        	],
        	[
        		'name' => 'order-delete',
        		'display_name' => 'Xóa đơn đặt hàng theo người dùng',
        		'description' => 'Xóa đơn đặt hàng theo người dùng'
			],
			// ORDERSHOPS
			[
        		'name' => 'ordershop-list',
        		'display_name' => 'Xem danh sách đơn đặt hàng theo cửa hàng',
        		'description' => 'Xem danh sách đơn đặt hàng theo cửa hàng'
        	],
			[
        		'name' => 'ordershop-show',
        		'display_name' => 'Hiển thị chi tiết đơn đặt hàng theo cửa hàng',
        		'description' => 'Hiển thị chi tiết đơn đặt hàng theo cửa hàng'
        	],
        	[
        		'name' => 'ordershop-create',
        		'display_name' => 'Tạo mới đơn đặt hàng theo cửa hàng',
        		'description' => 'Tạo mới đơn đặt hàng theo cửa hàng'
        	],
        	[
        		'name' => 'ordershop-edit',
        		'display_name' => 'Chỉnh sửa đơn đặt hàng theo cửa hàng',
        		'description' => 'Chỉnh sửa đơn đặt hàng theo cửa hàng'
        	],
        	[
        		'name' => 'ordershop-delete',
        		'display_name' => 'Xóa đơn đặt hàng theo cửa hàng',
        		'description' => 'Xóa đơn đặt hàng theo cửa hàng'
			],
			
			// RATES
			[
        		'name' => 'rate-list',
        		'display_name' => 'Xem danh sách tỷ giá',
        		'description' => 'Xem danh sách tỷ giá'
        	],
			[
        		'name' => 'rate-show',
        		'display_name' => 'Hiển thị chi tiết tỷ giá',
        		'description' => 'Hiển thị chi tiết tỷ giá'
        	],
        	[
        		'name' => 'rate-create',
        		'display_name' => 'Tạo mới tỷ giá',
        		'description' => 'Tạo mới tỷ giá'
        	],
        	[
        		'name' => 'rate-edit',
        		'display_name' => 'Chỉnh sửa tỷ giá',
        		'description' => 'Chỉnh sửa tỷ giá'
        	],
        	[
        		'name' => 'rate-delete',
        		'display_name' => 'Xóa đơn tỷ giá',
        		'description' => 'Xóa đơn tỷ giá'
			],
			// SETTINGS
			[
        		'name' => 'setting-list',
        		'display_name' => 'Xem danh sách cài đặt',
        		'description' => 'Xem danh sách cài đặt'
        	],
			[
        		'name' => 'setting-show',
        		'display_name' => 'Hiển thị chi tiết cài đặt',
        		'description' => 'Hiển thị chi tiết cài đặt'
        	],
        	[
        		'name' => 'setting-create',
        		'display_name' => 'Tạo mới cài đặt',
        		'description' => 'Tạo mới cài đặt'
        	],
        	[
        		'name' => 'setting-edit',
        		'display_name' => 'Chỉnh sửa cài đặt',
        		'description' => 'Chỉnh sửa cài đặt'
        	],
        	[
        		'name' => 'setting-delete',
        		'display_name' => 'Xóa đơn cài đặt',
        		'description' => 'Xóa đơn cài đặt'
        	]
        ];

        foreach ($permission as $key => $value) {
        	Permission::create($value);
        }
    }
}
