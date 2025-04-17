document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabs = document.querySelectorAll('.tabs li');
    if (tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                const tabContents = document.querySelectorAll('.tab-content');
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    }

    // Venue tabs in create event page
    const venueTabs = document.querySelectorAll('.venue-tab');
    if (venueTabs.length > 0) {
        venueTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const venueType = this.getAttribute('data-venue');
                const venueContents = document.querySelectorAll('.venue-content');
                
                // Remove active class from all tabs and contents
                venueTabs.forEach(t => t.classList.remove('active'));
                venueContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(venueType + '-venue').classList.add('active');
            });
        });
    }

    // Form step navigation in create event page
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const progressSteps = document.querySelectorAll('.progress-steps .step');

    if (nextButtons.length > 0) {
        nextButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const currentStep = document.querySelector('.form-step.active');
                const nextStep = currentStep.nextElementSibling;
                
                if (nextStep) {
                    currentStep.classList.remove('active');
                    nextStep.classList.add('active');
                    
                    // Update progress steps
                    progressSteps[index + 1].classList.add('active');
                    document.querySelectorAll('.step-line')[index].style.backgroundColor = 'var(--primary-color)';
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }

    if (prevButtons.length > 0) {
        prevButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const currentStep = document.querySelector('.form-step.active');
                const prevStep = currentStep.previousElementSibling;
                
                if (prevStep) {
                    currentStep.classList.remove('active');
                    prevStep.classList.add('active');
                    
                    // Update progress steps
                    progressSteps[index + 1].classList.remove('active');
                    document.querySelectorAll('.step-line')[index].style.backgroundColor = 'var(--border-color)';
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }

    // File upload preview
    const fileInput = document.getElementById('event-image');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileUpload = document.querySelector('.file-upload');
                    fileUpload.style.backgroundImage = `url(${e.target.result})`;
                    fileUpload.style.backgroundSize = 'cover';
                    fileUpload.style.backgroundPosition = 'center';
                    fileUpload.querySelector('.file-upload-content').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Add ticket type button
    const addTicketBtn = document.querySelector('.add-ticket');
    if (addTicketBtn) {
        addTicketBtn.addEventListener('click', function() {
            // In a real application, this would open a modal or form to add a new ticket type
            alert('This would open a form to add a new ticket type in a real application.');
        });
    }
});