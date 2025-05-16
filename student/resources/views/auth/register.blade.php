@extends('layouts.app')

@section('body_background', "url('" . asset('src/xu.png') . "') no-repeat center center fixed")

@section('content')

<style>
    body {
        position: relative;
        background-size: cover;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.3); 
        z-index: -1; 
    }
</style>

<script>
    // Set the background image using JavaScript
    document.body.style.background = "url('{{ asset('src/xu.png') }}') no-repeat center center fixed";
</script>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header" style="background-color: #17224D; color: white;">
                    <h3 class="mb-0">Register</h3>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="college" class="form-label">Select College</label>
                            <select class="form-control" id="college" name="college" required onchange="updateCourses()">
                                <option value="">Select College</option>
                                <option value="College of Arts and Sciences">College of Arts and Sciences</option>
                                <option value="School of Business and Management">School of Business and Management</option>
                                <option value="School of Education">School of Education</option>
                                <option value="College of Engineering">College of Engineering</option>
                                <option value="College of Agriculture">College of Agriculture</option>
                                <option value="College of Computer Studies">College of Computer Studies</option>
                                <option value="College of Nursing">College of Nursing</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Year Level</label>
                            <select class="form-control" id="year" name="year" required>
                                <option value="">Select Year Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="5th Year">5th Year</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="course" class="form-label">Select Course</label>
                            <select class="form-control" id="course" name="course" required>
                                <option value="">Select Course</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" style="background-color: #17224D;">Register</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <p class="mb-0">Already registered? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></p>
                    </div>
                </div>
            </div>
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
        var collegeSelect = document.getElementById('college');
        var courseSelect = document.getElementById('course');
        var selectedCollege = collegeSelect.value;

        courseSelect.innerHTML = '<option value="">Select Course</option>';

        if (coursesByCollege[selectedCollege]) {
            coursesByCollege[selectedCollege].forEach(function(course) {
                var option = document.createElement('option');
                option.value = course;
                option.text = course;
                courseSelect.add(option);
            });
        }
    }
</script>
@endsection