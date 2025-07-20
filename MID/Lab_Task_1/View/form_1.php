<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registeration Form</title>
</head>
<body>

<center>
        <h1 style="color: blue;">AIUB</h1>
        <h2 style="color: blue;">Course Registration Form</h2>
    </center>  

    <h3 align="left">Complete the Registration</h3>
    
    <table>

    <tr>
           
    <td> Enter Your Name: </td> <td> <input type="text"><br></td>
    </tr>

    <tr>
    <td>Email:</td> <td> <input type="text"> <br> </td>


    </tr>

    <tr>
    <td>Password:</td> <td> <input type="password"> <br> </td>


    </tr>


    <tr>
    <td>Gender:</td> 
    <td><input type="radio" name="des">Male
    <input type="radio" name="des">Other</td> <br>

    </tr>

    <tr>
        <td>Language Known:</td>
        <td> 
        <input type="checkbox">English
        <input type="checkbox">Bangla
        <input type="checkbox">Hindi



        </td>
    </tr>

    <tr>
        <td>Country:</td>
        <td>
             <select>

                <option value="">--Select--</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="India">India</option>
                <option value="Usa">USA</option>



            </select>


        </td>
    </tr>

    <tr>
        <td>Date of Birth:</td>
        <td>
        <input type="date">

        </td>
    </tr>

    <tr>
        <td>Upload Photo:</td>
        <td><input type="file"></td>
    </tr>

    <tr>
        <td>Comments:</td>
        <td><textarea cols="50" rows="10"></textarea></td><br>
        <!-- <td><input type="submit" value="Register"></td> -->

    </tr>

    <tr>
        <td></td>
        <td><input type="submit" value="Register"></td>
    </tr>



    </table>

    
        <!-- <input type="submit" value="Register"> -->
    

    

</body>
</html>