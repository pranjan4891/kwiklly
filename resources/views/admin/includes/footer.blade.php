<footer class="footer">
    {{ date('Y') }} &copy; Kwiklly
</footer>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/pace.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/wow.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.nicescroll.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/chat/moment-2.2.1.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/waypoints.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/easypie-chart/easypiechart.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/easypie-chart/jquery.easypiechart.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/easypie-chart/example.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/c3-chart/d3.v3.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/c3-chart/c3.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/morris/morris.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/morris/raphael.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/sparkline-chart/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/sparkline-chart/chart-sparkline.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/jquery.validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/jquery.validate/form-validation-init.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/sweet-alert/sweet-alert.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/sweet-alert/sweet-alert.init.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.app.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.chat.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.dashboard.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/js/jquery.todo.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/assets/admin/assets/assets/datatables/dataTables.bootstrap.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#datatable').dataTable();
        setTimeout(function() {
            $('.flashmsg').fadeOut('slow');
        }, 3000);
    });
</script>
<script>
    function clearForm(form) {
        document.getElementById(form).reset();
    }
</script>
jquery.dataTables.min.js
@stack('scripts')

</body>
</html>
