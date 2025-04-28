@extends('layouts.app')

@section('content')
<style>
    body {
        background: url('{{ asset('XU.png') }}') no-repeat center center fixed;
        background-size: cover;
        font-family: Arial, sans-serif;
    }

    .form-wrapper {
        max-width: 800px;
        margin: 5% auto;
        background: #e6edfb;
        padding: 40px;
        border-radius: 40px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .form-wrapper h2 {
        text-align: center;
        color: #1d1d8f;
        margin-bottom: 5px;
    }

    .form-wrapper p {
        text-align: center;
        color: #555;
        margin-bottom: 20px;
    }

    form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    form input, form select {
        width: 45%;
        padding: 10px;
        border: 2px solid #1d1d8f;
        border-radius: 10px;
        outline: none;
    }

    form button {
        width: 200px;
        padding: 10px;
        background-color: #1d1d8f;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 10px;
        margin-top: 20px;
        cursor: pointer;
    }

    form button:hover {
        background-color: #151579;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
    }

    .login-link a {
        color: #1d1d8f;
        font-weight: bold;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="form-wrapper">
    <h2>Register</h2>
    <p>Let's Register First!</p>

    @if($errors->any())
        <p style="color:red;">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>

        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="contact_number" placeholder="Phone Number" required>

        <select name="college" id="college" required onchange="updateCourses()">
            <option value="">Select College</option>
            <option value="College of Arts and Sciences">College of Arts and Sciences</option>
            <option value="School of Business and Management">School of Business and Management</option>
            <option value="School of Education">School of Education</option>
            <option value="College of Engineering">College of Engineering</option>
            <option value="College of Computer Studies">College of Computer Studies</option>
            <option value="College of Nursing">College of Nursing</option>
        </select>

        <select name="course" id="course" required>
            <option value="">Select Course</option>
        </select>

        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

        <button type="submit">Register</button>
    </form>

    <div class="login-link">
        <p>Already registered? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</div>

<script>
    const coursesByCollege = {
        "College of Arts and Sciences": [
            "AB in Psychology",
            "AB in Sociology – General Track",
            "AB in Sociology – Development Studies Track",
            "AB in Sociology – Advancement in Leadership (AL) Track",
            "AB in Literature (Track A: Literary and Cultural Studies)",
            "AB in Literature (Track B: Literature Across the Professions)",
            "AB in English Language (Track 1: English Language Studies as Discipline)",
            "AB in English Language (Track 2: English Language Across the Professions)",
            "AB in International Studies",
            "AB in History",
            "AB in Economics",
            "AB in Philosophy – Pre Law Track",
            "AB in Philosophy – Pre Divinity Track",
            "Bachelor of Science in Psychology",
            "Bachelor of Science in Biology",
            "Bachelor of Science in Development Communication",
            "Bachelor of Science in Marine Biology",
            "Bachelor of Science in Mathematics",
            "Bachelor of Science in Chemistry"
        ],
        "School of Business and Management": [
            "Bachelor of Science in Accountancy",
            "Bachelor of Science in Management Accounting",
            "Bachelor of Science in Business Administration Major in Business Economics",
            "Bachelor of Science in Business Administration Major in Financial Management",
            "Bachelor of Science in Business Administration Major in Marketing Management"
        ],
        "School of Education": [
            "Bachelor of Elementary Education",
            "Bachelor of Early Childhood Education",
            "Bachelor of Special Needs Education - Generalist",
            "Bachelor of Secondary Education Major in English",
            "Bachelor of Secondary Education Major in Mathematics",
            "Bachelor of Secondary Education Major in Science",
            "Bachelor of Secondary Education Major in Social Studies"
        ],
        "College of Engineering": [
            "Bachelor of Science in Chemical Engineering",
            "Bachelor of Science in Civil Engineering",
            "Bachelor of Science in Electrical Engineering",
            "Bachelor of Science in Mechanical Engineering",
            "Bachelor of Science in Electronics Engineering",
            "Bachelor of Science in Industrial Engineering"
        ],
        "College of Computer Studies": [
            "Bachelor of Science in Computer Science",
            "Bachelor of Science in Entertainment and Multimedia Computing",
            "Bachelor of Science in Information Systems",
            "Bachelor of Science in Information Technology"
        ],
        "College of Nursing": [
            "Bachelor of Science in Nursing"
        ]
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
