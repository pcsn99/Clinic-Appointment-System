@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="card info-card text-center p-4" style="background-color: #17224D; color: white;">
        <h2 class="fw-bold display-4">Welcome, Clinic Admin</h2>
    </div>



    <!-- Appointments Today & PIN Codes -->
    <div class="row mt-4">
        <!-- Appointments Today -->
        <div class="col-md-6">
            <div class="card appointment-card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">Appointments Today</h4>
                <p class="text-muted">{{ now()->format('F j, Y') }}</p>

                @if($appointmentsToday->isEmpty())
                    <p class="text-muted fs-5">No appointments scheduled for today.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Present</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointmentsToday as $appt)
                                    <tr>
                                        <td>{{ $appt->user->name }}</td>
                                        <td>{{ $appt->schedule->start_time }} - {{ $appt->schedule->end_time }}</td>
                                        <td>
                                            @if($appt->status === 'booked')
                                                <span class="badge bg-warning text-dark">Booked</span>
                                            @elseif($appt->status === 'completed')
                                                <span class="badge bg-success">Present</span>
                                            @elseif($appt->status === 'cancelled')
                                                <span class="badge bg-danger">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>{{ $appt->is_present ? 'Yes' : 'No' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- PIN Codes -->
        <div class="col-md-6">
            <div class="card schedule-card text-center p-4" style="border: 2px solid #17224D;">
                <h4 class="fw-bold display-5">PIN Codes</h4>
                <p class="text-muted">Valid for the current hour</p>

                <div class="fs-4 mt-3">
                    <p><strong>Attendance PIN:</strong> {{ $attendancePin->pin_code ?? 'Not Set' }}</p>
                    <p><strong>Slot Limit Override PIN:</strong> {{ $overridePin->pin_code ?? 'Not Set' }}</p>
                </div>
            </div>
        </div>
    </div>

      
    <div class="card mt-5 p-4 text-white" style="background-color: #17224D;">
        <h4 class="fw-bold text-center mb-4">System Statistics</h4>
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="border rounded p-3 h-100" style="background-color: rgba(255,255,255,0.1);">
                    <div class="fw-semibold">Registered Students</div>
                    <div class="fs-3 fw-bold">{{ $totalStudents }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="border rounded p-3 h-100" style="background-color: rgba(255,255,255,0.1);">
                    <div class="fw-semibold">Appointments Today</div>
                    <div class="fs-3 fw-bold">{{ $successfulToday }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="border rounded p-3 h-100" style="background-color: rgba(255,255,255,0.1);">
                    <div class="fw-semibold">This Week</div>
                    <div class="fs-3 fw-bold">{{ $successfulWeek }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="border rounded p-3 h-100" style="background-color: rgba(255,255,255,0.1);">
                    <div class="fw-semibold">This Month</div>
                    <div class="fs-3 fw-bold">{{ $successfulMonth }}</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mt-4 p-4 shadow-sm">
        <h4 class="fw-bold mb-3">Successful Appointments (Last 30 Days)</h4>
        <canvas id="appointmentChart" height="100"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('appointmentChart').getContext('2d');
    const appointmentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Completed Appointments',
                data: @json($chartData),
                borderColor: '#17224D',
                backgroundColor: 'rgba(23,34,77,0.1)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#17224D'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>

@endsection
