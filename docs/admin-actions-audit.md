# Admin actions audit (ThinkPHP source → Next.js)

Source: `Backend/docs/thinkphp-views/` + production `admin.wnskcex.com`

## User/index (`/users`)
Toolbar: Thêm mới, Đóng băng, Mở khóa, Bật rút, Tắt rút, Xóa, Gửi TB toàn hệ thống
Row: Sửa, Duyệt KYC, Gửi thông báo, +USDT, Đặt/Hủy đại lý

## User/coin (`/users/assets`)
Toolbar: Reset tìm kiếm
Row: Sửa (coinEdit)

## User/qianbao (`/users/wallets`)
Toolbar: Thêm mới, Xóa (bulk)
Row: Sửa

## User/log (`/users/login-logs`)
Toolbar: Thêm mới, Kích hoạt, Tạm ngừng, Xóa (bulk)
Row: Sửa

## User/amountlog (`/users/fund-history`)
Toolbar: Reset, Xóa (bulk)

## User/admin (`/users/admins`)
Toolbar: Thêm mới, Kích hoạt, Tạm ngừng, Xóa (bulk)
Row: Sửa

## User/agent (`/users/agents`)
Row: Sửa, Hủy đại lý

## User/noticelist (`/users/notices`)
Toolbar: Reset, Xóa (bulk)

## User/online (`/users/online-support`)
Row: Xem chi tiết → phản hồi

## Finance/myzr (`/finance/deposits`)
Row: Duyệt, Từ chối, Xóa

## Finance/myzc (`/finance/withdrawals`)
Row: Duyệt rút, Từ chối, Xóa

## Finance/index, fund
List only + reset search

## Trade/index (`/trading/orders`)
Row: set win/loss (kongyk)

## Trade/tyorder
Row: Kiểm tra (orderinfo_ty)

## Trade/sethy + queue
Queue panel actions (already partial)

## Config/coin, depositport, ctmarket, marketo, daohang, dhfooter
Toolbar: Thêm, Kích hoạt, Tạm ngừng, Xóa
Row: Sửa (+ Market/Robot for platform-markets)

## Kuangm/index
Toolbar: Thêm, Kích hoạt, Tạm ngừng, Xóa
Row: Sửa

## Kuangm/kjlist (`/miners/active`)
Toolbar: Bật thu nhập, Tạm thu nhập, Xóa máy đào (bulk)

## Article/index, News/index
Toolbar: Thêm, Xóa
Row: Sửa

## Issue/index
Row: Sửa gói staking
