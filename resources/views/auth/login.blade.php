@extends('base')

@section('content')
<div class="container p-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow futuristic-card">
        <div class="row">
            <!-- First Column with Image, Title, and Description -->

            <div class="col-md-6 d-flex flex-column justify-content-center align-items-center">
                <i class="material-icons md-48 text-primary mb-2">flight</i>
                <div class="text-center mb-4">
                    <h1 class="futuristic-text mb-2">SkyPassManager</h1>
                    <p class="text-muted">Your premier destination for convenient and hassle-free plane ticketing.</p>
                </div>
            </div>

            
            <!-- Second Column with Login Form -->
            <div class="col-md-6">
                <div class="card-header bg-dark">
                    <h1 class="text-center futuristic-text">Welcome SkyMember!</h1>
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="mb-3">
                            <label for="email" class="futuristic-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control futuristic-input" placeholder="Email">
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="futuristic-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control futuristic-input" placeholder="Password">
                            @error('password')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="d-flex mt-5">
                            <div class="flex-grow-1">
                                <a href="{{ route('registerForm') }}" class="futuristic-link">Create Account</a>
                            </div>
                            <button type="submit" class="btn btn-primary futuristic-button">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom CSS for Futuristic Login Styling */

    /* Add this link in the head section of your HTML file */
    /* <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"> */

    /* Background Gradient */
    body {
        background: linear-gradient(to right, #3498db, #2c3e50);
    }

    /* Card Styling */
    .futuristic-card {
        font-family: 'Roboto', sans-serif;
        background-color: #2c3e50;
        border: none;
        border-radius: 25px;
        box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.3);
        color: #ecf0f1;
        max-width: 1000px;
        margin: auto;
    }

    /* Header Text Styling */
    .futuristic-text {
        font-size: 36px;
        font-weight: bold;
        color: #ecf0f1;
    }

    /* Form Input Styling */
    .futuristic-input {
        font-family: 'Roboto', sans-serif;
        background-color: #2c3e50;
        
        border-radius: 5px;
        border: 1px solid #3498db; /* Bright blue border */
        color: #ecf0f1;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }

    /* Link Styling */
    .futuristic-link {
        font-family: 'Roboto', sans-serif;
        color: #1abc9c;
        text-decoration: none;
    }

    /* Button Styling */
    .futuristic-button {
        font-family: 'Roboto', sans-serif;
        background-color: #3498db;
        color: #ecf0f1;
        border: none;
        border-radius: 5px;
        padding: 12px 24px;
        cursor: pointer;
        transition: background-color 0.3s ease-in-out;
    }

    /* Link Hover Styling */
    .futuristic-link:hover {
        text-decoration: none;
        color: #16a085;
    }

    /* Button Hover Styling */
    .futuristic-button:hover {
        background-color: #2980b9;
    }
</style>
@endsection
