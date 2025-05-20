@extends('layouts.app')

@section('content')

<style>
    .container {
        max-width: 1000px;
    }

    .card {
        border: none;
        border-radius: 12px;
        padding: 40px;
        margin-bottom: 30px;
        background-color: #f8f9fa;
    }

    .info-card {
        background-color: #ffffff;
        border: 2px solid #dcdcdc;
    }

    .welcome-heading {
        color: #17224D;
        margin-bottom: 30px;
        text-align: center;
    }

    .action-btn-half {
        width: 48%;
        padding: 16px;
        font-size: 20px;
        font-weight: bold;
        border-radius: 12px;
    }

    .btn-primary {
        background-color: #17224D;
        border-color: #17224D;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .form-control {
        padding: 12px;
        font-size: 16px;
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: bold;
        font-size: 16px;
    }
</style>

<div class="container mt-5">
    <div class="card info-card">
        <h2 class="fw-bold display-4 welcome-heading">Edit Profile</h2>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $student->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $student->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="college" class="form-label">College</label>
                    @php
                        $colleges = [
                            "College of Arts and Sciences",
                            "School of Business and Management",
                            "School of Education",
                            "College of Engineering",
                            "College of Agriculture",
                            "College of Computer Studies",
                            "College of Nursing"
                        ];
                    @endphp
                    <select class="form-control @error('college') is-invalid @enderror" id="college" name="college" required onchange="updateCourses()">
                        <option value="">Select College</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college }}" {{ old('college', $student->college) == $college ? 'selected' : '' }}>{{ $college }}</option>
                        @endforeach
                    </select>
                    @error('college')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="course" class="form-label">Course</label>
                    <select class="form-control @error('course') is-invalid @enderror" id="course" name="course" required>
                        <option value="">Select Course</option>
                        <!-- Options will be populated by JS -->
                    </select>
                    @error('course')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                        <option value="">Select Year Level</option>
                        @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'] as $year)
                            <option value="{{ $year }}" {{ old('year', $student->year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $student->contact_number) }}" required>
                    @error('contact_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password (leave blank to keep current password)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('profile') }}" class="btn btn-secondary btn-lg action-btn-half">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg action-btn-half">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const coursesByCollege = {
        "College of Arts and Sciences": [
            "AB in Psychology", "AB in Sociology – General Track", "AB in Sociology – Development Studies Track",
            "AB in Sociology – Advancement in Leadership (AL) Track", "AB in Literature (Track A: Literary and Cultural Studies)",
            "AB in Literature (Track B: Literature Across the Professions)", "AB in English Language (Track 1: English Language Studies as Discipline)",
            "AB in English Language (Track 2: English Language Across the Professions)", "AB in International Studies", "AB in History",
            "AB in Economics", "AB in Philosophy – Pre Law Track", "AB in Philosophy – Pre Divinity Track", "Bachelor of Science in Psychology",
            "Bachelor of Science in Biology", "Bachelor of Science in Development Communication", "Bachelor of Science in Marine Biology",
            "Bachelor of Science in Mathematics", "Bachelor of Science in Chemistry"
        ],
        "School of Business and Management": [
            "Bachelor of Science in Accountancy", "Bachelor of Science in Management Accounting",
            "Bachelor of Science in Business Administration Major in Business Economics",
            "Bachelor of Science in Business Administration Major in Financial Management",
            "Bachelor of Science in Business Administration Major in Marketing Management"
        ],
        "School of Education": [
            "Bachelor of Elementary Education", "Bachelor of Early Childhood Education",
            "Bachelor of Special Needs Education - Generalist", "Bachelor of Secondary Education Major in English",
            "Bachelor of Secondary Education Major in Mathematics", "Bachelor of Secondary Education Major in Science",
            "Bachelor of Secondary Education Major in Social Studies"
        ],
        "College of Engineering": [
            "Bachelor of Science in Chemical Engineering", "Bachelor of Science in Civil Engineering",
            "Bachelor of Science in Electrical Engineering", "Bachelor of Science in Mechanical Engineering",
            "Bachelor of Science in Electronics Engineering", "Bachelor of Science in Industrial Engineering"
        ],
        "College of Agriculture": [
            "Bachelor of Science in Agribusiness", "Bachelor of Science in Agriculture Major in Animal Science",
            "Bachelor of Science in Agriculture Major in Crop Science", "Bachelor of Science in Food Technology",
            "Bachelor of Science in Agricultural and Biosystems Engineering"
        ],
        "College of Computer Studies": [
            "Bachelor of Science in Computer Science", "Bachelor of Science in Entertainment and Multimedia Computing with specialization in Digital Animation Technology",
            "Bachelor of Science in Information Systems", "Bachelor of Science in Information Technology"
        ],
        "College of Nursing": ["Bachelor of Science in Nursing"]
    };

    function updateCourses() {
        const collegeSelect = document.getElementById('college');
        const courseSelect = document.getElementById('course');
        const selectedCollege = collegeSelect.value;
        const selectedCourse = "{{ old('course', $student->course) }}";

        courseSelect.innerHTML = '<option value="">Select Course</option>';

        if (coursesByCollege[selectedCollege]) {
            coursesByCollege[selectedCollege].forEach(course => {
                const option = document.createElement('option');
                option.value = course;
                option.text = course;
                if (course === selectedCourse) {
                    option.selected = true;
                }
                courseSelect.appendChild(option);
            });
        }
    }

    window.onload = updateCourses;
</script>

@endsection
