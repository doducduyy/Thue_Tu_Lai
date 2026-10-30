@extends('layouts.master')
@section('title', 'Tổng quan hệ thống')
@section('content')
    <header class="page-heading">
        <div><span class="eyebrow">TRUNG TÂM ĐIỀU HÀNH</span><h1>Tổng quan</h1></div>
        <a class="button button-dark" href="{{ route('admin.vehicles.index') }}"><i class="fa-solid fa-car-side"></i> Quản lý đội xe</a>
    </header>

    <section class="stats-container" aria-label="Thống kê đội xe và doanh thu">
        <article class="stat-box"><span class="stat-icon"><i class="fa-solid fa-car-side"></i></span><h3>TỔNG SỐ XE</h3><div class="number">{{ $totalVehicles ?? 0 }}</div></article>
        <article class="stat-box"><span class="stat-icon"><i class="fa-solid fa-key"></i></span><h3>ĐANG THUÊ</h3><div class="number">{{ $rentedVehicles ?? 0 }}</div></article>
        <article class="stat-box"><span class="stat-icon"><i class="fa-solid fa-circle-check"></i></span><h3>SẴN SÀNG</h3><div class="number">{{ $availableVehicles ?? 0 }}</div></article>
        <article class="stat-box revenue-stat"><span class="stat-icon"><i class="fa-solid fa-chart-line"></i></span><h3>DOANH THU THÁNG NÀY</h3><div class="number">{{ number_format($currentMonthRevenue ?? 0, 0, ',', '.') }}đ</div></article>
    </section>

    <section class="card dashboard-chart-panel">
        <header class="dashboard-section-heading"><div><span class="eyebrow">HIỆU QUẢ KINH DOANH</span><h2>Doanh thu theo tháng</h2></div><span class="chart-year">{{ date('Y') }}</span></header>
        <div class="chart-canvas"><canvas id="revenueChart" aria-label="Biểu đồ doanh thu theo tháng"></canvas></div>
    </section>

    @if($selectedMonthName)
        <section class="card dashboard-invoices" id="detail_invoice_section">
            <header class="dashboard-section-heading"><div><span class="eyebrow">CHI TIẾT DOANH THU</span><h2>Hóa đơn đã thu — {{ $selectedMonthName }}</h2></div><a class="text-link" href="{{ route('dashboard') }}"><i class="fa-solid fa-xmark"></i> Đóng</a></header>
            @if($detailedInvoices->isEmpty())
                <div class="empty-state">Không có hóa đơn đã thanh toán trong tháng này.</div>
            @else
                <div class="table-scroll"><table class="data-table">
                    <thead><tr><th>NGÀY LẬP</th><th>XE THUÊ</th><th>KHÁCH HÀNG</th><th>TỔNG TIỀN</th><th>TRẠNG THÁI</th></tr></thead>
                    <tbody>@foreach($detailedInvoices as $invoice)
                        <tr><td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</td><td><strong>{{ $invoice->vehicle->name ?? 'Xe đã xóa' }}</strong><br><small>{{ $invoice->vehicle->license_plate ?? '' }}</small></td><td>{{ $invoice->contract->user->fullname ?? 'N/A' }}</td><td><strong>{{ number_format($invoice->total_amount, 0, ',', '.') }}đ</strong></td><td><span class="status-chip status-1"><i class="fa-solid fa-circle-check"></i> Đã thanh toán</span></td></tr>
                    @endforeach</tbody>
                </table></div>
            @endif
        </section>
        <script>document.getElementById('detail_invoice_section').scrollIntoView({ behavior: 'smooth' });</script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartLabels = {!! json_encode($chartLabels) !!};
        const revenueData = {!! json_encode($monthlyRevenueData) !!};
        const chartCanvas = document.getElementById('revenueChart');

        new Chart(chartCanvas, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: revenueData,
                    backgroundColor: '#245c48',
                    hoverBackgroundColor: '#d7ed80',
                    borderRadius: 5,
                    maxBarThickness: 44
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                onClick: (event, elements) => {
                    if (elements.length) {
                        window.location.href = `{{ route('dashboard') }}?selected_month=${elements[0].index}`;
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: value => `${Number(value).toLocaleString('vi-VN')}đ` },
                        grid: { color: '#edf0ec' }
                    },
                    x: { grid: { display: false } }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => `Doanh thu: ${Number(context.raw).toLocaleString('vi-VN')} VNĐ`
                        }
                    }
                }
            }
        });
    </script>
@endsection
