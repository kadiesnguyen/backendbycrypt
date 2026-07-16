# Laravel Scheduled Commands Documentation

## Tổng quan
Hệ thống đã được cấu hình với 5 scheduled commands để tự động xử lý các tác vụ định kỳ.

## Cấu hình Cron Job

### Cron đã được thiết lập
```bash
* * * * * cd /www/wwwroot/api.wnskcex.com && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

Cron job này chạy mỗi phút và Laravel scheduler sẽ tự động xác định command nào cần chạy dựa trên cấu hình trong `routes/console.php`.

### Kiểm tra cron job
```bash
crontab -l
```

## Danh sách Commands và Schedule

### 1. Process Hy Orders (Hợp đồng tương lai)
- **Command**: `app:process-orders`
- **Schedule**: Mỗi 15 giây
- **File**: `app/Console/Commands/ProcessHyOrders.php`
- **Mục đích**: Xử lý các hợp đồng hợp đồng tương lai đã hết hạn, cập nhật trạng thái, giá bán và kết quả thắng/thua
- **Chức năng chính**:
  - Lấy giá thị trường từ OKX API
  - Xác định thắng/thua dựa trên hướng mua (tăng/giảm)
  - Cộng tiền thắng hoặc hoàn tiền thua cho user
  - Hỗ trợ 3 chế độ: Auto, Force Win, Force Loss

### 2. Process Staking
- **Command**: `app:process-staking`
- **Schedule**: Mỗi 30 phút
- **File**: `app/Console/Commands/ProcessStaking.php`
- **Mục đích**: Xử lý các gói staking đã hết hạn
- **Chức năng chính**:
  - Bỏ đóng băng số dư USDT
  - Tính và cộng lợi nhuận staking
  - Ghi bill cho từng giao dịch
  - Cập nhật trạng thái staking

### 3. Reset Checkin Streak
- **Command**: `app:reset-checkin-streak`
- **Schedule**: Hàng ngày lúc 00:05
- **File**: `app/Console/Commands/ResetCheckinStreak.php`
- **Mục đích**: Kiểm tra và ghi log các user bỏ lỡ điểm danh hàng ngày
- **Chức năng chính**:
  - Tìm user đã điểm danh hôm qua nhưng chưa điểm danh hôm nay
  - Ghi log để admin theo dõi

### 4. Earning Daily Kuangji (Thu nhập hàng ngày từ máy đào)
- **Command**: `app:earning-daily-kuangji`
- **Schedule**: Mỗi 5 phút
- **File**: `app/Console/Commands/EarningDailyKuangji.php`
- **Mục đích**: Tính toán và phân phối thu nhập USDT hàng ngày cho các đơn hàng Kuangji đang hoạt động
- **Chức năng chính**:
  - Kiểm tra các đơn hàng Kuangji đang hoạt động
  - Đảm bảo đã qua 24 giờ kể từ lần nhận thưởng cuối
  - Cộng USDT hàng ngày vào tài khoản user
  - Ghi bill và cập nhật thời gian nhận thưởng

### 5. Process Limit Trade Orders (Lệnh giới hạn)
- **Command**: `app:process-limit-trade-orders`
- **Schedule**: Mỗi 15 giây
- **File**: `app/Console/Commands/ProcessLimitTradeOrders.php`
- **Mục đích**: Xử lý các lệnh giao dịch giới hạn khi giá thị trường đạt mục tiêu
- **Chức năng chính**:
  - Lấy giá thị trường từ Binance API
  - Kiểm tra điều kiện khớp lệnh (buy/sell)
  - Thực hiện giao dịch và cập nhật số dư
  - Tính phí giao dịch và hoàn tiền thừa

## Quản lý Schedule

### Xem danh sách schedule
```bash
php artisan schedule:list
```

### Chạy thủ công một command
```bash
# Process Hy Orders
php artisan app:process-orders

# Process Staking
php artisan app:process-staking

# Reset Checkin Streak
php artisan app:reset-checkin-streak

# Earning Daily Kuangji
php artisan app:earning-daily-kuangji

# Process Limit Trade Orders
php artisan app:process-limit-trade-orders
```

### Test schedule (không thực sự chạy)
```bash
php artisan schedule:test
```

### Chạy schedule thủ công
```bash
php artisan schedule:run
```

### Xem log của schedule
```bash
tail -f storage/logs/laravel.log
```

## Cấu hình Schedule (routes/console.php)

```php
Schedule::command('app:process-orders')->everyFifteenSeconds()->withoutOverlapping();
Schedule::command('app:process-staking')->everyThirtyMinutes()->withoutOverlapping();
Schedule::command('app:reset-checkin-streak')->dailyAt('00:05')->withoutOverlapping();
Schedule::command('app:earning-daily-kuangji')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('app:process-limit-trade-orders')->everyFifteenSeconds()->withoutOverlapping();
```

### Giải thích các tùy chọn:
- `everyFifteenSeconds()`: Chạy mỗi 15 giây
- `everyFiveMinutes()`: Chạy mỗi 5 phút
- `everyThirtyMinutes()`: Chạy mỗi 30 phút
- `dailyAt('00:05')`: Chạy hàng ngày lúc 00:05
- `withoutOverlapping()`: Ngăn command chạy nếu instance trước đó chưa hoàn thành

## Monitoring và Troubleshooting

### Kiểm tra xem cron có chạy không
```bash
grep CRON /var/log/syslog | tail -20
```

### Kiểm tra log Laravel
```bash
tail -f storage/logs/laravel.log | grep -E "(ProcessHyOrders|ProcessStaking|ResetCheckinStreak|EarningDailyKuangji|ProcessLimitTradeOrders)"
```

### Kiểm tra schedule có hoạt động không
```bash
php artisan schedule:work
```
Command này sẽ chạy scheduler trong foreground để bạn có thể xem output trực tiếp.

### Nếu schedule không chạy:
1. Kiểm tra cron job: `crontab -l`
2. Kiểm tra quyền thực thi: `ls -la artisan`
3. Kiểm tra PHP path: `which php`
4. Kiểm tra log: `tail -f storage/logs/laravel.log`
5. Test thủ công: `php artisan schedule:run`

## Lưu ý quan trọng

1. **Database Transactions**: Tất cả commands đều sử dụng database transactions để đảm bảo tính toàn vẹn dữ liệu
2. **Lock Mechanism**: Sử dụng `withoutOverlapping()` để tránh chạy đồng thời
3. **Error Handling**: Tất cả commands đều có try-catch và ghi log lỗi
4. **API Rate Limiting**: Commands gọi API bên ngoài (Binance, OKX) có timeout và error handling
5. **Performance**: Commands xử lý theo batch để tránh quá tải database

## Cập nhật Schedule

Nếu cần thay đổi tần suất chạy, chỉnh sửa file `routes/console.php`:

```php
// Ví dụ: Thay đổi từ mỗi 15 giây sang mỗi 30 giây
Schedule::command('app:process-orders')->everyThirtySeconds()->withoutOverlapping();

// Hoặc chạy mỗi phút
Schedule::command('app:process-orders')->everyMinute()->withoutOverlapping();

// Hoặc chạy mỗi 5 phút
Schedule::command('app:process-orders')->everyFiveMinutes()->withoutOverlapping();
```

Không cần restart gì cả, Laravel sẽ tự động load cấu hình mới ở lần chạy tiếp theo.

## Ngày tạo
2026-06-01

## Người tạo
Claude Code Assistant
