<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Management System</title>

    <style>
        body {
  background-color: #e9f2fa;
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  text-align: center;
}

.container {
  padding: 20px;
}

h1 {
  color: #003366;
  margin-bottom: 5px;
}

h2 {
  color: #0a3d62;
  font-weight: normal;
  margin-bottom: 30px;
}


.form-title {
  
  text-align: left;
  max-width: 480px;
  margin-top: 20px;
}

.registration-form {
  width: 480px;
  margin-left: 0;
  padding: 20px;
  background: #fff;
  border: 1px solid #ccc;
  text-align: left;
}

.form-group {
  display: flex;
  margin-bottom: 15px;
  align-items: center;
}

.form-group label {
  width: 160px;
  margin-right: 10px;
}

.form-group input,
.form-group select,
.form-group textarea {
  flex: 1;
  padding: 5px;
  font-size: 14px;
}

.checkbox-group {
  display: flex;
  justify-content: flex-start;
}

.checkbox-group label {
  width: auto;
}

/* Center the buttons below the form */
.button-center {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

button[type="reset"] {
  background-color: #007bff;
}

.overflow-box {
  margin-top: 20px;
  width: 300px;
  height: 60px;
  border: 1px solid #999;
  padding: 5px;
  overflow: auto;
  background-color: #fff;
  color: #111;
  text-align: left;
}


 .button-group{
  text-align: center;
 }

    </style>




</head>
<body>
    <div class="container">
        <h1>Bank Management System</h1>
        <h2>Your Trusted Financial Partner</h2>

        <div class="form-title">

          <h3>Customer Registration Form</h3>

          <form class="registration-form">
              <div class="form-group">
              <label>Full Name:</label>
              <input type="text" id="fullname">
              </div>

              <div class="form-group">
          <label for="dob">Date of Birth:</label>
          <input type="date" id="dob">
        </div>

        <div class="form-group">
          <label>Gender:</label>
          <div class="radio-group">
            <label><input type="radio" name="gender"> Male</label>
            <label><input type="radio" name="gender"> Female</label>
            <label><input type="radio" name="gender"> Other</label>
          </div>
        </div>


        <div class="form-group">
          <label for="marital">Marital Status:</label>
          <select id="marital">
            <option>Single</option>
            <option>Married</option>
            <option>Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="account">Account Type:</label>
          <select id="account">
            <option>Savings</option>
            <option>Current</option>
          </select>
        </div>

        <div class="form-group">
          <label for="deposit">Initial Deposit Amount:</label>
          <input type="number" id="deposit">
        </div>

        <div class="form-group">
          <label for="mobile">Mobile Number:</label>
          <input type="tel" id="mobile">
        </div>


        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email">
        </div>

        <div class="form-group">
          <label for="address">Address:</label>
          <textarea id="address" rows="2"></textarea>
        </div>

        <div class="form-group">
          <label for="occupation">Occupation:</label>
          <input type="text" id="occupation">
        </div>


        <div class="form-group">
          <label for="nid">National ID (NID):</label>
          <input type="text" id="nid">
        </div>

        <div class="form-group">
          <label for="password">Set Password:</label>
          <input type="password" id="password">
        </div>

        <div class="form-group">
          <label for="idproof">Upload ID Proof:</label>
          <input type="file" id="idproof">
        </div>

        <div class="form-group checkbox-group">
          <label><input type="checkbox"> I agree to the terms and conditions</label>
        </div>

        <div class="button-group">
          <button type="submit">Register</button>
          <button type="reset">Clear</button>
        </div>







          </form>

          <p class="overflow-box">This is a demo text to show how overflow works in a small container with a scroll bar. You can add more text here to make the scrollbar visible and test vertical and horizontal scrolling.</p>

        </div>
       

        








    </div>
    
</body>
</html>