@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Student Accounts</h2>
        
        <div class="actions-bar">
            <a href="{{ route('admin.dashboard') }}">
                <button>← Back to Dashboard</button>
            </a>
        </div>
        
        <div class="search-bar">
            <form action="{{ route('admin.students.index') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by name, username, email, course or year..." 
                    value="{{ $search ?? '' }}"
                >
                <button type="submit">Search</button>
                @if(!empty($search))
                    <a href="{{ route('admin.students.index') }}">
                        <button type="button">Clear</button>
                    </a>
                @endif
            </form>
        </div>
        
        <div class="students-list">
            @if($students->isEmpty())
                <p>No student accounts found.</p>
            @else
                <table border="1" cellpadding="6">
                    <thead>
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
                                        class="view-details-btn" 
                                        data-student-id="{{ $student->id }}"
                                        data-student-details="{{ $student->details_json }}"
                                    >
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="pagination">
                    <div class="pagination-info">
                        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} results
                    </div>
                    <div class="pagination-links">
                        @if ($students->onFirstPage())
                            <span class="pagination-button disabled">← Previous</span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="pagination-button">← Previous</a>
                        @endif
                        
                        <div class="pagination-pages">
                            @for ($i = 1; $i <= $students->lastPage(); $i++)
                                <a href="{{ $students->url($i) }}" class="pagination-page {{ $i == $students->currentPage() ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        </div>
                        
                        @if ($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="pagination-button">Next →</a>
                        @else
                            <span class="pagination-button disabled">Next →</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Student Details Modal -->
    <div id="studentModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Student Details</h3>
            
            <div id="studentInfo">
                <p><strong>Name:</strong> <span id="studentName"></span></p>
                <p><strong>Username:</strong> <span id="studentUsername"></span></p>
                <p><strong>Email:</strong> <span id="studentEmail"></span></p>
                <p><strong>Course:</strong> <span id="studentCourse"></span></p>
                <p><strong>Year:</strong> <span id="studentYear"></span></p>
                <p><strong>Contact Number:</strong> <span id="studentContact"></span></p>
            </div>
            
            <h4>Appointments</h4>
            <div id="appointmentsList">
                <table border="1" cellpadding="6" id="appointmentsTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Present</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentsTableBody">
                        <!-- Appointments will be loaded here -->
                    </tbody>
                </table>
                <p id="noAppointments" style="display: none;">No appointments found for this student.</p>
            </div>
        </div>
    </div>
    
    <style>
        .search-bar {
            margin: 20px 0;
        }
        
        .search-bar input {
            padding: 8px;
            width: 300px;
        }
        
        .actions-bar {
            margin-bottom: 20px;
        }
        
        /* Pagination Styles */
        .pagination {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: start;
        }
        
        .pagination-info {
            margin-bottom: 10px;
            color: #666;
        }
        
        .pagination-links {
            display: flex;
            align-items: center;
        }
        
        .pagination-button {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 3px;
        }
        
        .pagination-button.disabled {
            color: #999;
            cursor: not-allowed;
        }
        
        .pagination-pages {
            display: flex;
            margin: 0 10px;
        }
        
        .pagination-page {
            padding: 5px 10px;
            margin: 0 2px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 3px;
        }
        
        .pagination-page.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
    </style>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('studentModal');
            const closeBtn = document.querySelector('.close');
            const viewDetailsBtns = document.querySelectorAll('.view-details-btn');
            
            // Close modal when clicking the X
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
            
            // Close modal when clicking outside of it
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
            
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
                        modal.style.display = 'block';
                    } catch (error) {
                        console.error('Error parsing student details:', error);
                        alert('Failed to load student details. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection
