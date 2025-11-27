<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hóa đơn #{{ $thanhToan->ma_hoa_don }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .invoice-title { font-size: 20px; font-weight: bold; margin: 20px 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .items-table th { background-color: #f5f5f5; }
        .total-section { text-align: right; font-size: 16px; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-style: italic; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">JOE FITNESS CENTER</div>
        <div>123 Đường ABC, Quận XYZ, TP.HCM</div>
        <div>Hotline: 0909 123 456</div>
    </div>

    <div style="text-align: center;">
        <div class="invoice-title">HÓA ĐƠN THANH TOÁN</div>
        <div>Mã hóa đơn: {{ $thanhToan->ma_hoa_don }}</div>
        <div>Ngày: {{ $thanhToan->ngay_thanh_toan->format('d/m/Y H:i') }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Khách hàng:</strong><br>
                {{ $thanhToan->dangKyGoi->khachHang->nguoiDung->ho_ten }}<br>
                SĐT: {{ $thanhToan->dangKyGoi->khachHang->nguoiDung->sdt }}
            </td>
            <td width="50%">
                <strong>Người thu:</strong><br>
                {{ $thanhToan->nguoiThu->ho_ten }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Dịch vụ</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    {{ $thanhToan->dangKyGoi->goiTap->ten_goi }}<br>
                    <small>Số buổi: {{ $thanhToan->dangKyGoi->goiTap->so_buoi }}</small>
                </td>
                <td>{{ number_format($thanhToan->so_tien) }} đ</td>
                <td>{{ number_format($thanhToan->so_tien) }} đ</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        TỔNG CỘNG: {{ number_format($thanhToan->so_tien) }} VND
    </div>

    <div class="footer">
        <p>Cảm ơn quý khách đã sử dụng dịch vụ!</p>
        <p>Hóa đơn này chỉ có giá trị khi có chữ ký của người thu tiền.</p>
    </div>
</body>
</html>
