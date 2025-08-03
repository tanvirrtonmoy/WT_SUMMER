function validateDonationForm() {
    var firstName = document.getElementById("firstName").value.trim();
    var lastName = document.getElementById("lastName").value.trim();
    var address = document.getElementById("address").value.trim();
    var city = document.getElementById("city").value.trim();
    var state = document.getElementById("state").value;
    var phone = document.getElementById("phone").value.trim();
    var email = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var amountRadios = document.getElementsByName("amount");
    var otherAmount = document.getElementById("otherAmount").value.trim();


    if (!firstName || !lastName || !address || !city || !state || !phone || !email || !password || !confirmPassword) {
        alert("Please fill in all required fields.");
        return false;
    }

    
    var nameValid = /^[A-Za-z\s]+$/;
    if (!nameValid.test(firstName) || !nameValid.test(lastName)) {
        alert("Name must contain alphabets only.");
        return false;
    }


    var phonePattern = /^01\d{9}$/;
    if (!phonePattern.test(phone)) {
        alert("Phone number must be exactly 11 digits starting with 01.");
        return false;
    }

    
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Invalid email format.");
        return false;
    }

    
    var passwordValue = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordValue.test(password)) {
        alert("Password must contain at least 8 characters with uppercase, lowercase, digit, and special character.");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }


    var donationSelected = false;
    for (var i = 0; i < amountRadios.length; i++) {
        if (amountRadios[i].checked) {
            donationSelected = true;
            break;
        }
    }
    if (!donationSelected) {
        alert("Please select a donation amount.");
        return false;
    }

   
    if (selectedValue === "Other") {
        if (otherAmount === "" || isNaN(otherAmount) || parseFloat(otherAmount) <= 0) {
            alert("Please enter a valid donation amount in 'Other Amount'.");
            return false;
        }
    }

    

    alert("Thank you for your donation, " + firstName + "!");


    var infoMessage =
    "Submitted Information:\n" +
    "-----------------------------\n" +
    "Name: " + firstName + " " + lastName + "\n" +
    "Address: " + address + ", " + city + ", " + state + "\n" +
    "Phone: " + phone + "\n" +
    "Email: " + email + "\n" +
    "Donation Amount: " + donationAmount;

     alert(infoMessage);

    return true; 
}
