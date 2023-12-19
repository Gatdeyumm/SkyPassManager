@extends('base')

@section('content')
<div class="text-white">
    <header class="p-2" style="
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        background-color: #2c3e50;
        z-index: 1;
        position: fixed;
        width: 100%;
        color: #ffffff; /* Set text color to white */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Change to your preferred sans-serif font */
    ">
        <div class="d-flex justify-content-between align-items-center">
            <div class="">
                <h1 class="p-2" style="font-size: 1.5rem;"><i class="material-icons md-36 text-primary mb-2">flight</i><span> SkyPassManager</span></h1>
            </div>
            <div class="p-2">
                <a class="btn btn-hover text-white" href="/dashboard"><i class="fa-solid fa-ticket-alt"></i> Tickets</a>
                @role('admin')
                <a class="btn btn-hover text-white" href="/logs"><i class="fa-solid fa-file-alt"></i> Logs</a>
                @endrole

                <button class="btn btn-outline-danger rounded-pill text-white" id="logoutButton" data-toggle="modal" data-target="#confirmLogoutModal">
                    <i class="material-icons md-18 me-1">exit_to_app</i>
                        {{ Auth::user()->name }}
                </button>

            </div>
        </div>
    </header>

    <div>
    <div class="p-5">
        <div style="margin-top: 100px;">
            <form method="POST" action="{{ route('logs.clearAll') }}">
                <h1 class="d-flex justify-content-between">
                    @csrf
                    <button style="text-shadow: 0 0 10px white;" type="submit" class="btn btn-danger" data-toggle="modal" data-target="#clearAllLogsModal">
                        <i class="fas fa-times"></i> Clear All Logs
                    </button>
                </h1>
            </form>
            <br>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="table-responsive">
                    <table class="table table-dark table-hover" style="max-width: 100%;">
                        <tbody>
                            @foreach ($logEntries as $logEntry)
                                <tr>
                                    <td class="p-3 rounded-lg shadow-lg" style="background-color: #2c3e50;">
                                        <h5>{{$logEntry->log_entry}}</h5>
                                        {{-- Uncomment the following lines if you want to include a delete button for each log entry --}}
                                        <div class="float-right">
                                            <form method="POST" action="{{ route('log.delete', $logEntry->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="text-shadow: 0 0 10px white;" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div> 
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Confirm Logout -->
<div class="modal fade" style="margin-top: 100px;" id="confirmLogoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="background-color: #6c757d; color: #ffffff;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Confirm Logout</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to logout?
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="material-icons md-18 me-1">exit_to_app</i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
<style>
    /* Initial style for the entries */
    .entry {
        transform: translateX(-100%);
        opacity: 0;
        transition: transform 0.5s, opacity 0.5s;
    }

    /* Animation style for the entries when 'animated-page' class is applied */
    .animated-page .entry {
        transform: translateX(0);
        opacity: 1;
    }

    /* Add a transition for the 'animated-page' class to smooth the animation */
    .animated-page .entry {
        transition: transform 0.5s, opacity 0.5s;
    }

    .btn-hover:hover {
        background-color: #34495e; /* Change to the color you want on hover */
        transition: background-color 0.3s ease; /* Add a smooth transition effect */
    }

</style>
<script>
    // Add the 'animated-page' class to the parent container when the page is visited
    document.addEventListener('DOMContentLoaded', function() {
        const pageContainer = document.querySelector('.animated-page');
        pageContainer.classList.add('animated-page');
    });
</script>

@endsection
@auth

@endauth

