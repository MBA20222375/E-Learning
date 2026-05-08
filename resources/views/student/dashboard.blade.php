@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Enrolled Courses Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Enrolled Courses <span>| Total</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $enrolledCourses ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Enrolled Courses Card -->

                    <!-- Completed Courses Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Completed <span>| Courses</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $completedCourses ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Completed Courses Card -->

                    <!-- Certificates Card -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Certificates <span>| Earned</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-award"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $certificates ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Certificates Card -->

                    <!-- Reports Chart -->
                    <div class="col-12">
                        <div class="card">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start"><h6>Filter</h6></li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Learning Progress <span>| This Month</span></h5>
                                <div id="reportsChart"></div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new ApexCharts(document.querySelector("#reportsChart"), {
                                            series: [{
                                                name: 'Lessons Completed',
                                                data: [31, 40, 28, 51, 42, 82, 56],
                                            }, {
                                                name: 'Hours Spent',
                                                data: [11, 32, 45, 32, 34, 52, 41]
                                            }],
                                            chart: { height: 350, type: 'area', toolbar: { show: false } },
                                            markers: { size: 4 },
                                            colors: ['#4154f1', '#2eca6a'],
                                            fill: {
                                                type: "gradient",
                                                gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.4, stops: [0, 90, 100] }
                                            },
                                            dataLabels: { enabled: false },
                                            stroke: { curve: 'smooth', width: 2 },
                                            xaxis: {
                                                type: 'datetime',
                                                categories: ["2018-09-19T00:00:00.000Z","2018-09-19T01:30:00.000Z","2018-09-19T02:30:00.000Z","2018-09-19T03:30:00.000Z","2018-09-19T04:30:00.000Z","2018-09-19T05:30:00.000Z","2018-09-19T06:30:00.000Z"]
                                            },
                                            tooltip: { x: { format: 'dd/MM/yy HH:mm' } }
                                        }).render();
                                    });
                                </script>
                            </div>
                        </div>
                    </div><!-- End Reports Chart -->

                    <!-- Continue Learning -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start"><h6>Filter</h6></li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Continue Learning <span>| In Progress</span></h5>
                                <table class="table table-borderless datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Instructor</th>
                                            <th scope="col">Progress</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                No courses in progress yet.
                                                <a href="#" class="d-block mt-1">Browse Courses</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="text-center mt-2">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="bi bi-search me-1"></i> Browse All Courses
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Continue Learning -->

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Recent Activity -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start"><h6>Filter</h6></li>
                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity <span>| Today</span></h5>
                        <div class="activity">
                            <div class="activity-item d-flex">
                                <div class="activite-label">32 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">Completed lesson <a href="#" class="fw-bold text-dark">Introduction to Laravel</a></div>
                            </div>
                            <div class="activity-item d-flex">
                                <div class="activite-label">1 hr</div>
                                <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                <div class="activity-content">Started new course <a href="#" class="fw-bold text-dark">Advanced PHP</a></div>
                            </div>
                            <div class="activity-item d-flex">
                                <div class="activite-label">2 hrs</div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <div class="activity-content">Quiz submitted for review</div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Recent Activity -->

                <!-- Course Progress Chart -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start"><h6>Filter</h6></li>
                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>
                    <div class="card-body pb-0">
                        <h5 class="card-title">Course Progress <span>| Overview</span></h5>
                        <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                echarts.init(document.querySelector("#trafficChart")).setOption({
                                    tooltip: { trigger: 'item' },
                                    legend: { top: '5%', left: 'center' },
                                    series: [{
                                        name: 'Courses',
                                        type: 'pie',
                                        radius: ['40%', '70%'],
                                        avoidLabelOverlap: false,
                                        label: { show: false, position: 'center' },
                                        emphasis: { label: { show: true, fontSize: '18', fontWeight: 'bold' } },
                                        labelLine: { show: false },
                                        data: [
                                            { value: {{ $completedCourses ?? 0 }}, name: 'Completed' },
                                            { value: {{ $enrolledCourses ?? 0 }}, name: 'In Progress' }
                                        ]
                                    }]
                                });
                            });
                        </script>
                    </div>
                </div><!-- End Course Progress Chart -->

            </div><!-- End Right side columns -->

        </div>
    </section>

@endsection
