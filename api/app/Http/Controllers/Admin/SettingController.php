<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Hiển thị danh sách cài đặt
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Setting::all();
        
        // Nhóm cài đặt theo group
        $groupedSettings = $settings->groupBy('group');
        
        return response()->json($groupedSettings);
    }

    /**
     * Cập nhật cài đặt
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.group' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $settings = $request->input('settings');
        
        foreach ($settings as $setting) {
            Setting::setValue(
                $setting['key'],
                $setting['value'] ?? null,
                $setting['group'] ?? null
            );
        }
        
        return response()->json(['message' => 'Đã cập nhật cài đặt thành công']);
    }

    /**
     * Lấy cài đặt theo group
     *
     * @param  string  $group
     * @return \Illuminate\Http\Response
     */
    public function getByGroup($group)
    {
        $settings = Setting::where('group', $group)->get();
        
        // Chuyển đổi thành mảng key-value
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting->key] = $setting->value;
        }
        
        return response()->json($settingsArray);
    }

    /**
     * Lấy giá trị cài đặt theo key
     *
     * @param  string  $key
     * @return \Illuminate\Http\Response
     */
    public function getByKey($key)
    {
        $value = Setting::getValue($key);
        
        return response()->json(['key' => $key, 'value' => $value]);
    }

    /**
     * Khởi tạo các cài đặt mặc định
     *
     * @return \Illuminate\Http\Response
     */
    public function initializeDefaults()
    {
        // Cài đặt thông tin cửa hàng
        Setting::setValue('store_name', 'Shop Điện Thoại', 'store');
        Setting::setValue('store_description', 'Cửa hàng bán điện thoại uy tín', 'store');
        Setting::setValue('store_address', '123 Đường ABC, Quận XYZ, TP.HCM', 'store');
        Setting::setValue('store_phone', '0123456789', 'store');
        Setting::setValue('store_email', 'contact@shopdienthoai.com', 'store');
        
        // Cài đặt email
        Setting::setValue('email_from', 'no-reply@shopdienthoai.com', 'email');
        Setting::setValue('email_from_name', 'Shop Điện Thoại', 'email');
        
        // Cài đặt thanh toán
        Setting::setValue('payment_cod', 'true', 'payment');
        Setting::setValue('payment_banking', 'true', 'payment');
        Setting::setValue('payment_momo', 'false', 'payment');
        Setting::setValue('payment_zalopay', 'false', 'payment');
        
        // Cài đặt vận chuyển
        Setting::setValue('shipped_fee', '30000', 'shipped');
        Setting::setValue('free_shipped_min', '500000', 'shipped');
        
        return response()->json(['message' => 'Đã khởi tạo cài đặt mặc định thành công']);
    }
} 