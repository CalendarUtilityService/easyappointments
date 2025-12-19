<?php
/**
 * Welcome Modal Component
 *
 * Shows a welcome message on the user's first visit to the booking page.
 * Uses localStorage to track if the modal has been shown.
 */
?>

<div id="welcome-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>Welcome to the ScheduCal Demo</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    This is a version of the open source appointment software EasyAppointments that has been modified to work with ScheduCal.
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
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    Got it!
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if welcome modal has been shown before
        if (!localStorage.getItem('scheducal_welcome_shown')) {
            // Show the modal after a short delay
            setTimeout(function() {
                var welcomeModal = new bootstrap.Modal(document.getElementById('welcome-modal'));
                welcomeModal.show();
                localStorage.setItem('scheducal_welcome_shown', 'true');
            }, 500);
        }
    });

    // Debug function to reset the welcome modal (accessible from console)
    window.resetWelcomeModal = function() {
        localStorage.removeItem('scheducal_welcome_shown');
        console.log('Welcome modal reset. Refresh the page to see it again.');
    };
</script>
