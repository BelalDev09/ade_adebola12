<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('Backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Other plugins -->
<script src="{{ asset('Backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('Backend/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('Backend/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('Backend/assets/js/plugins.js') }}"></script>

<!-- App JS -->
<script src="{{ asset('Backend/assets/js/app.js') }}"></script>
<script src="{{ asset('Backend/assets/js/layout.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
