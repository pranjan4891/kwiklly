<!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Are you sure you want to log out?</p>
                        <p>Press No if you want to continue work. Press Yes to logout current user.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="pull-right">
                            <a href="{{ route('vendor.logout') }}" class="btn btn-success btn-lg">Yes</a>
                            <button class="btn btn-default btn-lg mb-control-close">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->

        <!-- START PRELOADS -->
        <audio id="audio-alert" src="{{ asset('public/assets/vendor/audio/alert.mp3') }} " preload="auto"></audio>
        <audio id="audio-fail" src="{{ asset('public/assets/vendor/audio/fail.mp3') }}" preload="auto"></audio>
        <!-- END PRELOADS -->

    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/jquery/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/jquery/jquery-ui.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap.min.js') }}"></script>
        <!-- END PLUGINS -->

        <!-- START THIS PAGE PLUGINS-->
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/icheck/icheck.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/scrolltotop/scrolltopcontrol.js') }}"></script>

        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/morris/raphael-min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/morris/morris.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/rickshaw/d3.v3.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/rickshaw/rickshaw.min.js') }}"></script>
        <script type='text/javascript' src="{{ asset('public/assets/vendor/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
        <script type='text/javascript' src="{{ asset('public/assets/vendor/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
        <script type='text/javascript' src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap-datepicker.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/owl/owl.carousel.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap-datepicker.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap-timepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap-colorpicker.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap-file-input.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/bootstrap/bootstrap-select.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/tagsinput/jquery.tagsinput.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins/summernote/summernote.js') }}"></script>
        <!-- END THIS PAGE PLUGINS-->

        <!-- START TEMPLATE -->
        <!-- <script type="text/javascript" src="{{ asset('public/assets/vendor/js/settings.js') }}"></script> -->

        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/plugins.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/actions.js') }}"></script>

        <script type="text/javascript" src="{{ asset('public/assets/vendor/js/demo_dashboard.js') }}"></script>

        {{-- // Select2 --}}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->
    </body>
</html>
