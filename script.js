// Welcome popup when website loads

window.onload = function(){

    alert("Welcome to QuickBite Food Delivery! Enjoy our fresh meals.");

};




// Form validation

document.getElementById("userForm").addEventListener("submit", function(event){


    event.preventDefault();



    let name = document.getElementById("name").value;

    let email = document.getElementById("email").value;

    let phone = document.getElementById("phone").value;

    let gender = document.getElementById("gender").value;



    // Email format example:
    // stacey20@gmail.com

    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;



    if(name.trim() === ""){

        alert("Please enter your name");

        return;

    }



    if(!emailPattern.test(email)){

        alert("Invalid email. Use format example: stacey20@gmail.com");

        return;

    }



    if(phone.length < 10){

        alert("Please enter a valid phone number");

        return;

    }



    if(gender === ""){

        alert("Please select your gender");

        return;

    }




    alert(
        "Registration Successful!\n\n" +
        "Name: " + name +
        "\nEmail: " + email +
        "\nPhone: " + phone +
        "\nGender: " + gender
    );



});
