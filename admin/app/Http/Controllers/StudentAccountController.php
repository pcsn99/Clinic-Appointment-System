<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentAccountController extends Controller
{
    /**
     * Display a listing of student accounts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = User::where('role', 'student');
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhere('year', 'like', "%{$search}%");
            });
        }
        
        // Get all students first to apply custom sorting
        $allStudents = $query->get();
        
        // Custom sorting function for student names with numbers
        $sortedStudents = $allStudents->sort(function ($a, $b) {
            // Extract numbers from names for numerical comparison
            preg_match('/(\d+)/', $a->name, $aMatches);
            preg_match('/(\d+)/', $b->name, $bMatches);
            
            $aNum = isset($aMatches[1]) ? (int)$aMatches[1] : 0;
            $bNum = isset($bMatches[1]) ? (int)$bMatches[1] : 0;
            
            // If both names contain numbers, sort numerically
            if ($aNum > 0 && $bNum > 0) {
                return $aNum - $bNum;
            }
            
            // If only one name has a number or neither has numbers, sort alphabetically
            return strcmp($a->name, $b->name);
        });
        
        // Paginate the sorted collection manually
        $page = $request->input('page', 1);

        $perPage = 7; // Changed from 15 to 7 to prevent scrolling
        $total = $sortedStudents->count();
        
        // Create a custom pagination instance with fixed parameters to ensure consistent width

        $students = new \Illuminate\Pagination\LengthAwarePaginator(
            $sortedStudents->forPage($page, $perPage),
            $total,
            $perPage,
            $page,

            [
                'path' => $request->url(), 
                'query' => array_merge($request->query(), ['width' => 'fixed'])
            ]

        );
        
        // Add a data attribute with student details for each student to avoid AJAX
        foreach ($students as $student) {
            $appointments = Appointment::with(['schedule'])
                ->where('user_id', $student->id)
                ->orderBy('created_at', 'desc')
                ->get();
                
            $appointmentsData = $appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->schedule->date,
                    'time' => $appointment->schedule->start_time . ' - ' . $appointment->schedule->end_time,
                    'status' => $appointment->status,
                    'is_present' => $appointment->is_present ? 'Yes' : 'No',
                    'created_at' => $appointment->created_at->format('M d, Y h:i A'),
                ];
            });
            
            $student->details_json = json_encode([
                'student' => $student,
                'appointments' => $appointmentsData
            ]);
        }
        
        return view('students.index', compact('students', 'search'));
    }

    /**
     * Get student details with appointments for AJAX request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getStudentDetails($id)
    {
        try {
            // Log the request for debugging
            Log::info('Student details requested for ID: ' . $id);
            
            $student = User::findOrFail($id);
            
            $appointments = Appointment::with(['schedule'])
                ->where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $response = [
                'student' => $student,
                'appointments' => $appointments->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'date' => $appointment->schedule->date,
                        'time' => $appointment->schedule->start_time . ' - ' . $appointment->schedule->end_time,
                        'status' => $appointment->status,
                        'is_present' => $appointment->is_present ? 'Yes' : 'No',
                        'created_at' => $appointment->created_at->format('M d, Y h:i A'),
                    ];
                }),
            ];
            
            // Log the response for debugging
            Log::info('Student details response: ', ['student_id' => $id, 'appointment_count' => count($appointments)]);
            
            return response()->json($response);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error fetching student details: ' . $e->getMessage(), [
                'student_id' => $id,
                'exception' => $e
            ]);
            
            return response()->json(['error' => 'Failed to load student details'], 500);
        }
    }
}
