// Get modal elements for User Modal (existing one)
var modal = document.getElementById('userModal');
var btn = document.getElementById('addUserBtn');
var span = document.getElementsByClassName('close')[0];
var form = document.getElementById('userForm');
var modalTitle = document.getElementById('modalTitle');
var saveBtn = document.getElementById('saveBtn');
var passwordLabel = document.getElementById('passwordLabel');
var passwordField = document.getElementById('password');

// Add user button opens the modal (User Modal)
btn.onclick = function() {
    form.reset(); // Reset the form
    modalTitle.innerText = 'Add User';
    passwordLabel.style.display = 'block'; // Show password field
    passwordField.style.display = 'block'; // Show password input
    passwordLabel.innerText = 'Password'; // Set the label text
    saveBtn.name = 'add_user'; // Set the save button to "Add User"
    
    // Clear the image input (in case it's left from a previous session)
    document.getElementById('user_profile').value = '';

    modal.style.display = 'block'; // Show modal
}

// Close the User Modal
span.onclick = function() {
    modal.style.display = 'none';
}

// Close the User Modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// When the Edit button is clicked
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.getAttribute('data-id');
        const firstName = this.getAttribute('data-first-name');
        const lastName = this.getAttribute('data-last-name');
        const contactNumber = this.getAttribute('data-contact-number');
        const email = this.getAttribute('data-email');
        const type = this.getAttribute('data-type');
        const password = this.getAttribute('data-password'); // Fetch hashed password (not used for editing)

        // Set the modal fields
        document.getElementById('users_id').value = userId;
        document.getElementById('first_name').value = firstName;
        document.getElementById('last_name').value = lastName;
        document.getElementById('contact_number').value = contactNumber;
        document.getElementById('email').value = email;
        document.getElementById('type').value = type;

        // Clear password field (user should input new password if needed)
        document.getElementById('password').value = ''; // Leave it empty for the user to fill if they want to change it
        passwordLabel.innerText = 'New Password (leave blank to keep current)'; // Change label for editing
        passwordField.style.display = 'block';  // Ensure password field is visible in edit mode

        // Clear the image input in case the user does not want to change it
        document.getElementById('user_profile').value = '';

        // Change modal title to "Edit User"
        modalTitle.innerText = 'Edit User';

        // Ensure the "Save" button will trigger the edit action
        saveBtn.setAttribute('name', 'edit_user'); // Update form action to edit_user
        
        // Show the modal
        modal.style.display = 'block';
    });
});

// Get modal elements for Activity Modal
var activityModal = document.getElementById('activityModal');
var activityClose = activityModal.querySelector('.close');
var activityBody = document.getElementById('activityBody');

// View Activity button opens the Activity Modal
var activityButtons = document.getElementsByClassName('view-activity-btn');
for (var i = 0; i < activityButtons.length; i++) {
    activityButtons[i].onclick = function() {
        var userId = this.getAttribute('data-id');
        
        // Use AJAX to fetch the activity data for this user
        fetch('fetch_user_activity.php?user_id=' + userId)
            .then(response => response.json())
            .then(data => {
                // Clear previous activity rows in case of multiple users
                activityBody.innerHTML = '';

                // Check if there are activity records
                if (data.length > 0) {
                    // Loop through the activity data and display each record in the table
                    data.forEach(function(activity) {
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${activity.login_time ? activity.login_time : 'N/A'}</td>
                            <td>${activity.logout_time ? activity.logout_time : 'N/A'}</td>
                        `;
                        activityBody.appendChild(row);
                    });
                } else {
                    // Display a message if no activity is found
                    var row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="2" style="text-align:center;">No activity recorded for this user.</td>
                    `;
                    activityBody.appendChild(row);
                }
                
                // Show the activity modal
                activityModal.style.display = 'block';
            })
            .catch(error => console.error('Error fetching activity data:', error));
    }
}

// Close the Activity Modal
activityClose.onclick = function() {
    activityModal.style.display = 'none';
}

// Close the Activity Modal when clicking outside of it
window.onclick = function(event) {
    if (event.target == activityModal) {
        activityModal.style.display = 'none';
    }
}

