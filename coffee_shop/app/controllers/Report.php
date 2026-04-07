<?php
class Report extends Controller {
    private $reportModel;

    public function __construct() {
        // Chỉ Admin mới được xem Báo cáo doanh thu
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header("Location: " . URLROOT . "/dashboard");
            exit;
        }
        $this->reportModel = $this->model('ReportModel');
    }

    // Hiển thị giao diện báo cáo
    public function index() {
        // Mặc định lấy từ đầu tháng đến ngày hiện tại
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $reportData = $this->reportModel->getRevenueByDateRange($startDate, $endDate);

        $data = [
            'title' => 'Báo Cáo Doanh Thu',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'report_list' => $reportData['list'],
            'total_revenue' => $reportData['total_revenue'],
            'total_orders' => $reportData['total_orders']
        ];

        $this->view('admin/report', $data);
    }

    // Chức năng xuất file Excel (CSV Format)
    public function exportExcel() {
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $reportData = $this->reportModel->getRevenueByDateRange($startDate, $endDate);

        // Thiết lập Header để trình duyệt hiểu đây là file tải về
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=BaoCaoDoanhThu_' . $startDate . '_den_' . $endDate . '.csv');

        // Mở output stream
        $output = fopen('php://output', 'w');

        // Thêm ký tự BOM (Byte Order Mark) để Microsoft Excel nhận diện đúng font UTF-8 Tiếng Việt
        fputs($output, "\xEF\xBB\xBF");

        // Ghi dòng tiêu đề cột
        fputcsv($output, ['Ngày', 'Số lượng đơn hàng', 'Doanh thu (VNĐ)']);

        // Lặp dữ liệu và ghi từng dòng
        foreach ($reportData['list'] as $row) {
            fputcsv($output, [
                date('d/m/Y', strtotime($row['order_date'])),
                $row['total_orders'],
                number_format($row['daily_revenue'], 0, ',', '.') . ' đ'
            ]);
        }

        // Dòng tổng cộng
        fputcsv($output, ['']);
        fputcsv($output, [
            'TỔNG CỘNG', 
            $reportData['total_orders'], 
            number_format($reportData['total_revenue'], 0, ',', '.') . ' đ'
        ]);

        fclose($output);
        exit;
    }
}
?>