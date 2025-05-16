@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 1200px;">
        {{-- Styled Title Box --}}
        <div class="text-center mb-4 p-3 rounded" style="background-color: #162163; color: white; font-weight: bold; font-size: 24px;">
            Upcoming Appointments
        </div>

        {{-- Row Layout for Calendar and Table --}}
        <div class="row">
            {{-- 📅 FullCalendar (Left Side) --}}
            <div class="col-md-6">
                <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
                <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

                <div id="calendar" style="width: 100%; border: 1px solid #ccc; padding: 10px;"></div>
            </div>

            {{-- 📝 Appointment Table (Right Side) --}}
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
                </div>
            </div>
        </div>
    </div>

    {{-- 📆 FullCalendar Script --}}
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
                            } else {
                                const rows = appointments.map(appt => `
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
                                            <form method="POST" action="/admin/appointments/${appt.id}/mark">
                                                @csrf
                                                <input type="hidden" name="is_present" value="${appt.is_present ? 0 : 1}">
                                                <button type="submit" class="btn btn-sm ${appt.is_present ? 'btn-secondary' : 'btn-success'}"
                                                        onclick="return confirm('${appt.is_present ? 'Revert to Booked?' : 'Mark as Present?'}')">
                                                    ${appt.is_present ? 'Revert' : 'Mark Present'}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                `).join('');

                                document.getElementById('appointmentsTable').innerHTML = rows;
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