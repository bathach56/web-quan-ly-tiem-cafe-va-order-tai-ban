<?php
class ReportModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Lấy doanh thu theo khoảng thời gian
    public function getRevenueByDateRange($startDate, $endDate) {
        // Cố định thêm giờ phút giây để lấy trọn vẹn ngày cuối cùng
        $start = $startDate . ' 00:00:00';
        $end = $endDate . ' 23:59:59';

        // Lấy danh sách doanh thu từng ngày
        $this->db->query("
            SELECT DATE(created_at) as order_date, COUNT(id) as total_orders, SUM(total_amount) as daily_revenue
            FROM orders
            WHERE status = 'completed' AND created_at >= :start_date AND created_at <= :end_date
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at) DESC
        ");
        $this->db->bind(':start_date', $start);
        $this->db->bind(':end_date', $end);
        $list = $this->db->resultSet();

        // Tính tổng cộng doanh thu và tổng đơn của cả giai đoạn
        $total_revenue = 0;
        $total_orders = 0;
        foreach ($list as $item) {
            $total_revenue += $item['daily_revenue'];
            $total_orders += $item['total_orders'];
        }

        return [
            'list' => $list,
            'total_revenue' => $total_revenue,
            'total_orders' => $total_orders
        ];
    }
}
?>