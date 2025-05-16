@extends('layouts.app')

@section('content')
<div class="container">

    <h2>System Logs Viewer</h2>


    <h4 class="mt-4">Admin System Log</h4>
    <pre style="background:#111;color:#eee;padding:1rem;border-radius:8px;max-height:300px;overflow:auto;">
        @foreach($adminLogs as $line)
            {{ $line }}
        @endforeach
    </pre>

    <h4 class="mt-4">Student System Log</h4>
    <pre style="background:#111;color:#eee;padding:1rem;border-radius:8px;max-height:300px;overflow:auto;">
        @foreach($studentLogs as $line)
            {{ $line }}
        @endforeach
    </pre>

    <h4 class="mt-4">System Health Log</h4>
        <pre style="background:#111;color:#eee;padding:1rem;border-radius:8px;max-height:300px;overflow:auto;">
            @foreach($healthLogs as $line)
                {{ $line }}
            @endforeach
        </pre>

</div>
@endsection

