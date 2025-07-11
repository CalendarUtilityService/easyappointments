<div id="welcome-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>Welcome to the ScheduCal Demo</strong></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>
                    This is a version of the open source appointment software EasyAppointments that has been modified to work with SheduCal. 
                    The original version <a href="https://demo.easyappointments.org/" target="_blank">(here)</a> sends out emails with ICS files. 
                    This version sends calendar appointments directly to your calendar.
                </p>
                <p>
                    NOTE: all data is periodically deleted from this site. It is intended for testing purposes only.
                </p>
                <p>
                    <strong>If you don't receive appointments in your inbox, please check your spam folder.</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Got it!
                </button>
                <!-- Remove this button when you confirm the modal is working -->
                <button type="button" id="test-reset-welcome" class="btn btn-secondary" style="display:none;">
                    Reset (Testing)
                </button>
            </div>
            <script>
                $(document).ready(function() {
                    // Add event handler for test button
                    $('#test-reset-welcome').on('click', function() {
                        localStorage.removeItem('scheducal_welcome_shown');
                        alert('Welcome modal reset! Refresh the page to see it again.');
                    });
                });
            </script>
        </div>
    </div>
</div>