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
    <div class="p-3">
        <div style="margin-top: 100px;">
            <h1 class="d-flex text-white float-end" style="padding: 5px; border-radius: 5px;">
                @role('admin')
                    <button style="font-size: 18px; border: none; padding: 8px 16px;" type="button" class="btn text-white btn-success btn-hover" data-toggle="modal" data-target="#exampleModal">
                        <i class="material-icons md-18 me-1">add</i> Add Ticket
                    </button>
                @endrole
            </h1>
            <br>

            <div style="place-content: center;" class="d-flex flex-wrap">
                @foreach ($tickets as $ticket)
                <div class="m-2">
                    <div class="m-5">           
                        <!--for the booking card when the book now is clicked-->                  
                        <div class="booking-card" id="bookingCard_{{ $ticket->id }}" style="border-radius: 20px; height: 160px; text-align: center; width: 200px; color: #fff; background-color: #3498db; display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); z-index: 1000;">
                            <div class="spinner-border text-light" role="status" style="margin-bottom: 10px;">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h5 style="margin-top: 10px; font-size: 13px; color: #8eff9e;">
                                <span class="text-success"><i class="fas fa-check-circle"></i></span> Your ticket is being processed. Please check your email shortly.
                            </h5>
                        </div>

                        <form id="bookingForm_{{ $ticket->id }}" action="{{ route('ticket.book', $ticket) }}" method="POST">
                            @csrf
                            <div class="view-button">
                                @role('user')
                                    <button style="background-color: #c9e3fa; color: #3498db; border: none; border-radius: 5px; padding: 5px 5px;" class="btn text-primary" data-ticket-id="{{ $ticket->id }}" onclick="submitBookingForm(this)">
                                        <i class="fas fa-ticket-alt"></i> Book Now
                                    </button>
                                @endrole
                            </div>
                        </form>    
                    </div>
                    <div class="">
                        <div class="p-2" style="background-color: #f8f8f8; border: 2px solid #ccc; height: 230px; border-radius: 10px; max-width: 400px; margin: auto;">
                            <div style="display: flex; justify-content: space-between; padding: 5px; border-bottom: 2px solid #ccc;">
                                <div style="flex-grow: 1;">
                                    <h5 style="margin: 0;color: #333; font-weight: bold;"><i class="material-icons md-48 text-primary mb-2" style="transform: rotate(45deg);">flight</i>
{{$ticket->name}}</h5>
                                    <p style="margin: 0; color: #333;">From: {{$ticket->from}}</p>
                                    <p style="margin: 0; color: #333;">To: {{$ticket->to}}</p>
                                </div>
                                <div style="flex-grow: 1; text-align: right;">
                                    <h5 style="margin: 0; color: #e44d26;">Price: ${{$ticket->price}}</h5>
                                    <p style="margin: 0; color: #333;">Departure: {{$ticket->departure_time}}</p>
                                    <p style="margin: 0; color: #333;">Duration: {{$ticket->duration}}</p>
                                </div>
                            </div>
                            <div style="padding: 10px; text-align: center;">
                                <p style="margin: 0; color: #333;">Country: {{$ticket->country}}</p>
                                <p style="margin: 0; color: #333;">City: {{$ticket->city}}</p>
                                <p style="margin-bottom: 7px; color: #333;">Arrival: {{$ticket->arrival_time}}</p>
                
                                    @role('admin')
                                        <button type="button" class="btn btn-success orderBtn" data-toggle="modal" data-target="#editModal-{{ $ticket->id }}" style="font-size: 14px;">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button type="button" class="btn btn-danger orderBtn" data-toggle="modal" data-target="#deleteModal-{{ $ticket->id }}" data-ticket-id="{{ $ticket->id }}" style="font-size: 14px;">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    @endrole
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Edit Modal -->
                <div id="editModal-{{ $ticket->id }}" class="modal fade" style="margin-top: 100px;" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="background-color: #2c3e50; color: #ecf0f1;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Ticket</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm-{{ $ticket->id }}" method="POST" action="{{ route('tickets.update', $ticket) }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="name" name="name" value="{{ $ticket->name }}">
                                            </div>

                                            <!-- Add other form fields for the left column here -->
                                            <div class="form-group">
                                                <label for="from">From:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="from" name="from" value="{{ $ticket->from }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="to">To:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="to" name="to" value="{{ $ticket->to }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="country">Country:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="country" name="country" value="{{ $ticket->country }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Add form fields for the right column here -->
                                            <div class="form-group">
                                                <label for="city">City:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="city" name="city" value="{{ $ticket->city }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="price">Price:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="price" name="price" value="{{ $ticket->price }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="departure_time">Departure Time:</label>
                                                <input type="datetime-local" class="form-control bg-transparent text-white" id="departure_time" name="departure_time" value="{{ $ticket->departure_time }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="arrival_time">Arrival Time:</label>
                                                <input type="datetime-local" class="form-control bg-transparent text-white" id="arrival_time" name="arrival_time" value="{{ $ticket->arrival_time }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="duration">Duration:</label>
                                                <input type="text" class="form-control bg-transparent text-white" id="duration" name="duration" value="{{ $ticket->duration }}">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" form="editForm-{{ $ticket->id }}" class="btn btn-success"><i class="fa fa-save"></i> Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>



            

                <!-- Delete Modal -->
                <div id="deleteModal-{{ $ticket->id }}" class="modal fade" style="margin-top: 100px;" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="background-color: #6c757d; color: #ffffff;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete this Ticket?
                            </div>
                            <div class="modal-footer">
                                <form id="deleteForm-{{ $ticket->id }}" method="POST" action="{{ route('tickets.destroy', $ticket) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
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


            <!-- CREATE  Modal -->
            <div class="modal fade" style="margin-top: 100px" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="background-color: #2c3e50; color: #ecf0f1;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><b>Create Ticket</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form method="POST" action="{{ route('tickets.store') }}">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" name="name" id="name" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="from">From:</label>
                                            <input type="text" name="from" id="from" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="to">To:</label>
                                            <input type="text" name="to" id="to" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="country">Country:</label>
                                            <input type="text" name="country" id="country" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">City:</label>
                                            <input type="text" name="city" id="city" class="form-control bg-transparent text-white" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Price:</label>
                                            <input type="number" name="price" id="price" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="departure_time">Departure Time:</label>
                                            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="arrival_time">Arrival Time:</label>
                                            <input type="datetime-local" name="arrival_time" id="arrival_time" class="form-control bg-transparent text-white" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Duration:</label>
                                            <input type="text" name="duration" id="duration" class="form-control bg-transparent text-white" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



<style>
    .orderBtn{
        font-size: 20px;
        transition: 0.5s
    }
    .orderBtn:hover{
        font-size: 30px;
    }

    .booking-card {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 2px;
    }


     .m-5 {
        perspective: 1000px;
        transition: transform 0.5s;
    }

    .bg-secondary {
        border-radius: 20px;
        box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
        transition: transform 0.5s;
    }

    .bg-secondary:hover {
        transform: rotateY(20deg);
    }

    .view-button {
        position: fixed;
        top: 20%;
        left: 20%;
        width: 50%;
        height: 20%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-hover:hover {
        background-color: #34495e; /* Change to the color you want on hover */
        transition: background-color 0.3s ease; /* Add a smooth transition effect */
    }

</style>
<script>
// Function to show the "Ordering Please Wait!" card and submit the form
function submitBookingForm(button) {
    var ticketId = button.getAttribute('data-ticket-id');
    var bookingCard = document.getElementById('bookingCard_' + ticketId);
    bookingCard.style.display = 'block';

    // Submit the form after showing the card
    var bookingForm = document.getElementById('bookingForm_' + ticketId);
    bookingForm.submit();
}


   function updateSelectedSoftware() {
        const checkboxes = document.querySelectorAll('input[name="software"]');
        const selectedSoftware = [];

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedSoftware.push(checkbox.value);
            }
        });

        const selectedSoftwareInput = document.getElementById('daws');
        selectedSoftwareInput.value = selectedSoftware.join(', '); // or use any other delimiter you prefer
    }
</script>

@endsection
@auth

@endauth

