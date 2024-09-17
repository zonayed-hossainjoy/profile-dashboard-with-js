function validateRegistrationForm() {
    let valid = true;
    const errors = {};

    const form = document.forms['registerForm'];
    const fullName = form['fullName'].value.trim();
    const email = form['email'].value.trim();
    const password = form['password'].value;
    const confirmPassword = form['confirmPassword'].value;
    const contactNo = form['contactNo'].value;
    const gender = form['gender'].value;

    if (!fullName) {
        errors['fullName'] = "Full name is required.";
        valid = false;
    }
    if (!email || !validateEmail(email)) {
        errors['email'] = "Valid email is required.";
        valid = false;
    }
    if (!password) {
        errors['password'] = "Password is required.";
        valid = false;
    }
    if (password !== confirmPassword) {
        errors['confirmPassword'] = "Passwords do not match.";
        valid = false;
    }
    if (contactNo === '' || !/^\d{1,14}$/.test(contactNo)) {
        errors['contactNo'] = "Contact number must be up to 14 digits.";
        valid = false;
    }
    if (!gender) {
        errors['gender'] = "Gender is required.";
        valid = false;
    }

    showErrors(errors);

    return valid;
}

function validateProfileForm() {
    let valid = true;
    const errors = {};

    const form = document.forms['profileForm'];
    const fullName = form['fullName'].value.trim();
    const contactNo = form['contactNo'].value;

    if (!fullName) {
        errors['fullName'] = "Full name is required.";
        valid = false;
    }
    if (contactNo === '' || !/^\d{1,14}$/.test(contactNo)) {
        errors['contactNo'] = "Contact number must be up to 14 digits.";
        valid = false;
    }

    showErrors(errors);

    return valid;
}

function validatePasswordForm() {
    let valid = true;
    const errors = {};

    const form = document.forms['passwordForm'];
    const oldPassword = form['oldPassword'].value;
    const newPassword = form['newPassword'].value;
    const confirmNewPassword = form['confirmNewPassword'].value;

    if (!oldPassword) {
        errors['oldPassword'] = "Old password is required.";
        valid = false;
    }
    if (!newPassword) {
        errors['newPassword'] = "New password is required.";
        valid = false;
    }
    if (newPassword !== confirmNewPassword) {
        errors['confirmNewPassword'] = "New passwords do not match.";
        valid = false;
    }
    if (newPassword.length < 6) {
        errors['newPassword'] = "New password must be at least 6 characters long.";
        valid = false;
    }

    showErrors(errors);

    return valid;
}

function showErrors(errors) {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(el => el.textContent = '');

    for (const key in errors) {
        const errorElement = document.getElementById('error-' + key);
        if (errorElement) {
            errorElement.textContent = errors[key];
        }
    }
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
