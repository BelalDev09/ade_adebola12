@extends('backend.app')

@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-4">Contact Us Today</h2>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4">
                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Type your name here">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="Type your email address here">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Topic</label>
                            <input type="text" name="topic" class="form-control" placeholder="Type your topic here">
                        </div>

                        <div class="mb-3">
                            <label>Leave us a message</label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Type your message here"></textarea>
                        </div>

                        <button class="btn btn-primary w-100">SEND</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Map Section --}}
        <div class="mt-5">
            <h3 class="text-center mb-3">Where to Find Us</h3>

            <iframe src="https://www.google.com/maps?q=Mohakhali%20Medona%20Tower,Dhaka&output=embed" width="100%"
                height="350" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Include toastr CSS & JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif
        });
    </script>
@endpush
