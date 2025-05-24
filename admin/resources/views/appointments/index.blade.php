@extends('layouts.app')

@section('content')
<style>
    .appointments-container {
        width: 900px;
        margin: 0 auto;
    }
    .appointments-header {
        background-color: #17224D;
        color: white;
        text-align: center;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
</style>

<div class="appointments-container">
    <div class="card shadow-lg mb-4">
        <div class="card-header text-center text-white" style="background-color: #17224D;">
            <h2 class="fw-bold display-5 my-2">Upcoming Appointments</h2>
        </div>
        <div class="card-body p-4">

        {{-- Row Layout for Calendar and Table --}}
        <div class="row">
            {{-- FullCalendar (Left Side) --}}
            <div class="col-md-6">
                <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
                <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

                <div id="calendar" style="width: 100%; border: 1px solid #ccc; padding: 10px;"></div>
            </div>

            {{-- Appointment Table (Right Side) --}}
            <div class="col-md-6">
                <div class="p-3 border rounded" style="background-color: white;">
                    <h5 class="text-center">Appointments for <span id="selectedDate">Select a date</span></h5>

                    {{-- Success/Error Messages --}}
                    @if(session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger text-center">{{ session('error') }}</div>
                    @endif

                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <span>Show </span>
                            <select id="appointmentsPerPage" class="form-select form-select-sm d-inline-block w-auto">
                                <option value="4" selected>4</option>
                                <option value="8">8</option>
                                <option value="12">12</option>
                            </select>
                            <span> entries</span>
                        </div>
                        <div>
                            <span id="paginationInfo">Showing 0 to 0 of 0 entries</span>
                        </div>
                    </div>
                    
                    <table class="table table-bordered">
                        <thead class="text-white text-center" style="background-color: #162163;">
                            <tr>
                                <th>Student</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Present</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="appointmentsTable">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No appointments selected.</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button id="prevPage" class="btn btn-sm btn-secondary" disabled>
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <div id="paginationPages" class="btn-group">
                           
                        </div>
                        <button id="nextPage" class="btn btn-sm btn-secondary" disabled>
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

    {{-- FullCalendar Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                events: '/appointments/calendar-events',
                dateClick: function (info) {
                    const date = info.dateStr;
                    document.getElementById('selectedDate').textContent = date;
                    document.getElementById('appointmentsTable').innerHTML = '<tr><td colspan="5" class="text-center">Loading...</td></tr>';

                    fetch(`/appointments/schedules-by-date?date=${date}`)
                        .then(res => {
                            if (!res.ok) {
                                throw new Error("Failed to fetch appointments.");
                            }
                            return res.json();
                        })
                        .then(appointments => {
                            if (!Array.isArray(appointments) || appointments.length === 0) {
                                document.getElementById('appointmentsTable').innerHTML = '<tr><td colspan="5" class="text-center text-muted">No appointments found.</td></tr>';
                                document.getElementById('paginationInfo').textContent = 'Showing 0 to 0 of 0 entries';
                                document.getElementById('prevPage').disabled = true;
                                document.getElementById('nextPage').disabled = true;
                                document.getElementById('paginationPages').innerHTML = '';
                            } else {
                                
                                let allAppointments = appointments;
                                let currentPage = 1;
                                let itemsPerPage = parseInt(document.getElementById('appointmentsPerPage').value);
                                
                                
                                function generateAppointmentRow(appt) {
                                    return `
                                        <tr class="text-center">
                                            <td>${appt.student_name}</td>
                                            <td>${appt.start_time} - ${appt.end_time}</td>
                                            <td>
                                                <span class="badge ${appt.status === 'booked' ? 'bg-warning text-dark' : appt.status === 'completed' ? 'bg-success' : 'bg-danger'}">
                                                    ${appt.status.charAt(0).toUpperCase() + appt.status.slice(1)}
                                                </span>
                                            </td>
                                            <td>${appt.is_present ? 'Yes' : 'No'}</td>
                                            <td>
                                                <form method="POST" action="${window.location.origin}/appointments/${appt.id}/mark">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="is_present" value="${appt.is_present ? 0 : 1}">
                                                    <button type="submit" class="btn btn-sm ${appt.is_present ? 'btn-secondary' : 'btn-success'}"
                                                            onclick="return confirm('${appt.is_present ? 'Revert to Booked?' : 'Mark as Present?'}')">
                                                        ${appt.is_present ? 'Revert' : 'Mark Present'}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    `;
                                }
                                
                                
                                function renderAppointments() {
                                    const startIndex = (currentPage - 1) * itemsPerPage;
                                    const endIndex = Math.min(startIndex + itemsPerPage, allAppointments.length);
                                    const paginatedAppointments = allAppointments.slice(startIndex, endIndex);
                                    
                                    
                                    document.getElementById('paginationInfo').textContent = 
                                        `Showing ${startIndex + 1} to ${endIndex} of ${allAppointments.length} entries`;
                                    
                                    
                                    const rows = paginatedAppointments.map(generateAppointmentRow).join('');
                                    document.getElementById('appointmentsTable').innerHTML = rows;
                                    
                                    
                                    updatePaginationButtons();
                                }
                                
                                
                                function updatePaginationButtons() {
                                    const totalPages = Math.ceil(allAppointments.length / itemsPerPage);
                                    document.getElementById('prevPage').disabled = currentPage === 1;
                                    document.getElementById('nextPage').disabled = currentPage === totalPages;
                                    
                                   
                                    let pagesHTML = '';
                                    for (let i = 1; i <= totalPages; i++) {
                                        pagesHTML += `<button class="btn btn-sm ${currentPage === i ? 'btn-primary' : 'btn-outline-secondary'}" 
                                                      onclick="setPage(${i})">${i}</button>`;
                                    }
                                    document.getElementById('paginationPages').innerHTML = pagesHTML;
                                }
                                
                              
                                window.setPage = function(page) {
                                    currentPage = page;
                                    renderAppointments();
                                };
                                
                               
                                document.getElementById('prevPage').addEventListener('click', function() {
                                    if (currentPage > 1) {
                                        currentPage--;
                                        renderAppointments();
                                    }
                                });
                                
                                document.getElementById('nextPage').addEventListener('click', function() {
                                    const totalPages = Math.ceil(allAppointments.length / itemsPerPage);
                                    if (currentPage < totalPages) {
                                        currentPage++;
                                        renderAppointments();
                                    }
                                });
                                
                                document.getElementById('appointmentsPerPage').addEventListener('change', function() {
                                    itemsPerPage = parseInt(this.value);
                                    currentPage = 1; 
                                    renderAppointments();
                                });
                                
                                
                                renderAppointments();
                            }
                        })
                        .catch(error => {
                            document.getElementById('appointmentsTable').innerHTML = `<tr><td colspan="5" class="text-center text-danger">${error.message}</td></tr>`;
                        });
                }
            });

            calendar.render();
        });
    </script>
@endsection