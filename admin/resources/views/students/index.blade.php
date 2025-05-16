@extends('layouts.app')

@section('content')

<style>
    .student-accounts-container {
        width: 900px;
        margin: 0 auto;
    }
    .student-table {
        width: 100%;
        table-layout: fixed;
    }
    .student-table th, .student-table td {
        padding: 10px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .student-table th:nth-child(1), .student-table td:nth-child(1) { width: 20%; } /* Name */
    .student-table th:nth-child(2), .student-table td:nth-child(2) { width: 15%; } /* Username */
    .student-table th:nth-child(3), .student-table td:nth-child(3) { width: 25%; } /* Email */
    .student-table th:nth-child(4), .student-table td:nth-child(4) { width: 15%; } /* Course */
    .student-table th:nth-child(5), .student-table td:nth-child(5) { width: 10%; } /* Year */
    .student-table th:nth-child(6), .student-table td:nth-child(6) { width: 15%; } /* Actions */
</style>

<div class="student-accounts-container">
    <div class="card shadow-lg mb-4">
            <div class="card-header text-center text-white" style="background-color: #17224D;">
                <h2 class="fw-bold display-5 my-2">Student Accounts</h2>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <form action="{{ route('admin.students.index') }}" method="GET" class="d-flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search by name, username, email, course or year..." 
                            value="{{ $search ?? '' }}"
                        >
                        <button type="submit" class="btn btn-primary">Search</button>
                        @if(!empty($search))
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
        
                @if($students->isEmpty())
                    <div class="alert alert-info">No student accounts found.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover student-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Year</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->username }}</td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->course }}</td>
                                        <td>{{ $student->year }}</td>
                                        <td>
                                            <button 
                                                class="btn btn-primary btn-sm view-details-btn" 
                                                data-student-id="{{ $student->id }}"
                                                data-student-details="{{ $student->details_json }}"
                                            >
                                                <i class="bi bi-eye"></i> View Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
                    <div class="mt-4 w-100">
                        <div class="d-flex justify-content-start align-items-center mb-2">
                            <div class="pagination-info">
                                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} results
                            </div>
                        </div>
                        
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                @if ($students->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">← Previous</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $students->previousPageUrl() }}">← Previous</a></li>
                                @endif
                                
                                @for ($i = 1; $i <= $students->lastPage(); $i++)
                                    <li class="page-item {{ $i == $students->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $students->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                
                                @if ($students->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $students->nextPageUrl() }}">Next →</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Next →</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>

        </div>
    </div>
    
    <!-- Student Details Modal -->

    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #17224D; color: white;">
                    <h5 class="modal-title fw-bold" id="studentModalLabel">Student Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Personal Information</h5>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Name:</div>
                                <div class="col-md-9" id="studentName"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Username:</div>
                                <div class="col-md-9" id="studentUsername"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Email:</div>
                                <div class="col-md-9" id="studentEmail"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Course:</div>
                                <div class="col-md-9" id="studentCourse"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Year:</div>
                                <div class="col-md-9" id="studentYear"></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Contact Number:</div>
                                <div class="col-md-9" id="studentContact"></div>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-3">Appointments</h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="appointmentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Present</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTableBody">
                                <!-- Appointments will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info" id="noAppointments" style="display: none;">No appointments found for this student.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const studentModal = new bootstrap.Modal(document.getElementById('studentModal'));
            const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
            

            // Add click event to all "View Details" buttons
            viewDetailsBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // Get the student details from the data attribute
                    const studentDetailsJson = this.getAttribute('data-student-details');
                    
                    try {
                        // Parse the JSON data
                        const data = JSON.parse(studentDetailsJson);
                        console.log('Student data loaded from attribute:', data);
                        
                        // Populate student info
                        document.getElementById('studentName').textContent = data.student.name;
                        document.getElementById('studentUsername').textContent = data.student.username;
                        document.getElementById('studentEmail').textContent = data.student.email;
                        document.getElementById('studentCourse').textContent = data.student.course;
                        document.getElementById('studentYear').textContent = data.student.year;
                        document.getElementById('studentContact').textContent = data.student.contact_number || 'Not provided';
                        
                        // Populate appointments
                        const appointmentsTableBody = document.getElementById('appointmentsTableBody');
                        const noAppointments = document.getElementById('noAppointments');
                        
                        appointmentsTableBody.innerHTML = '';
                        
                        if (data.appointments && data.appointments.length > 0) {
                            data.appointments.forEach(function(appointment) {
                                const row = document.createElement('tr');
                                
                                // Add status color
                                let statusColor = '';
                                if (appointment.status === 'booked') {
                                    statusColor = 'orange';
                                } else if (appointment.status === 'completed') {
                                    statusColor = 'green';
                                } else if (appointment.status === 'cancelled') {
                                    statusColor = 'red';
                                }
                                
                                row.innerHTML = `
                                    <td>${appointment.date}</td>
                                    <td>${appointment.time}</td>
                                    <td><span style="color: ${statusColor}">${appointment.status}</span></td>
                                    <td>${appointment.is_present}</td>
                                    <td>${appointment.created_at}</td>
                                `;
                                
                                appointmentsTableBody.appendChild(row);
                            });
                            
                            document.getElementById('appointmentsTable').style.display = 'table';
                            noAppointments.style.display = 'none';
                        } else {
                            document.getElementById('appointmentsTable').style.display = 'none';
                            noAppointments.style.display = 'block';
                        }
                        
                        // Show modal

                        studentModal.show();

                    } catch (error) {
                        console.error('Error parsing student details:', error);
                        alert('Failed to load student details. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
