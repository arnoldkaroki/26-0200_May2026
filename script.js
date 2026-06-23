// ═══════════════════════════════════════════
// QUICKBITE FOOD DELIVERY JAVASCRIPT
// ═══════════════════════════════════════════


// ─── Open and Close Modals ───

function openModal(id) {
    document.getElementById(id).classList.add("active");
}


function closeModal(id) {
    document.getElementById(id).classList.remove("active");
}





// ─── QuickBite Promo Sound ───

function playPromoSound() {

    try {

        const AudioContext =
        window.AudioContext || window.webkitAudioContext;


        if (!AudioContext) return;


        const ctx = new AudioContext();


        if (ctx.state === "suspended") {
            ctx.resume();
        }



        const notes = [

            {freq: 880, start:0, duration:0.18},

            {freq:1175, start:0.16, duration:0.30}

        ];



        notes.forEach(function(note){


            const oscillator = ctx.createOscillator();

            const gain = ctx.createGain();



            oscillator.type = "sine";

            oscillator.frequency.value = note.freq;



            const time = ctx.currentTime + note.start;



            gain.gain.setValueAtTime(
                0.0001,
                time
            );


            gain.gain.exponentialRampToValueAtTime(
                0.25,
                time + 0.02
            );


            gain.gain.exponentialRampToValueAtTime(
                0.0001,
                time + note.duration
            );



            oscillator.connect(gain);

            gain.connect(ctx.destination);



            oscillator.start(time);

            oscillator.stop(
                time + note.duration
            );


        });


    }

    catch(error){

        console.log(
        "Sound unavailable"
        );

    }

}




// ─── Show QuickBite Promotion ───

function showPromo(){

    openModal("promo-modal");

    playPromoSound();

}





// ─── Automatic Popups ───

window.addEventListener("load",function(){



    // Welcome message

    setTimeout(function(){

        openModal("welcome-modal");


    },1500);





    // Promo message

    setTimeout(function(){


        if(
        !document
        .getElementById("welcome-modal")
        .classList.contains("active")
        ){


            showPromo();


        }

        else{


            setTimeout(function(){

                showPromo();

            },3000);


        }



    },8000);



});







// ─── Close modal by clicking outside ───


document.addEventListener(
"DOMContentLoaded",
function(){


document
.querySelectorAll(".modal-overlay")
.forEach(function(modal){


modal.addEventListener(
"click",
function(event){


if(event.target === modal){

modal.classList.remove("active");


}


});


});


});








// ─── Close popup using ESC key ───


document.addEventListener(
"keydown",
function(event){


if(event.key === "Escape"){


document
.querySelectorAll(".modal-overlay.active")
.forEach(function(modal){


modal.classList.remove("active");


});


}


});








// ═══════════════════════════════════════════
// QUICKBITE REGISTRATION VALIDATION
// ═══════════════════════════════════════════




// Email validation

function isValidEmail(email){


const emailPattern =
/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


return emailPattern.test(email);


}





// Name validation

function isValidName(name){


const namePattern =
/^[a-zA-Z\s]{2,}$/;


return namePattern.test(
name.trim()
);


}





// Phone validation

function isValidPhone(phone){


const phonePattern =
/^\+?[0-9]{10,13}$/;


return phonePattern.test(
phone.replace(/\s/g,"")
);


}







// Show errors

function showError(field,message){


let error =
document.getElementById(field+"-error");


let input =
document.getElementById(field);



if(input){

input.classList.add("invalid");

}



if(error){

error.innerHTML = message;

error.classList.add("show");

}


}







// Remove errors

function clearError(field){


let error =
document.getElementById(field+"-error");


let input =
document.getElementById(field);



if(input){

input.classList.remove("invalid");

}



if(error){

error.classList.remove("show");

}


}







// ─── Registration Form ───


document.addEventListener(
"DOMContentLoaded",
function(){



const form =
document.getElementById("signup-form");



if(form){



form.addEventListener(
"submit",
function(event){


event.preventDefault();



let name =
document.getElementById("signup-name")
.value.trim();



let email =
document.getElementById("signup-email")
.value.trim();



let phone =
document.getElementById("signup-phone")
.value.trim();



let gender =
document.getElementById("signup-gender")
.value;



let valid=true;





// Name check

if(!isValidName(name)){


showError(
"signup-name",
"Enter a valid name"
);


valid=false;


}

else{


clearError(
"signup-name"
);


}







// Email check

if(!isValidEmail(email)){


showError(
"signup-email",
"Enter a valid email e.g stacey20@gmail.com"
);


valid=false;


}

else{


clearError(
"signup-email"
);


}







// Phone check

if(!isValidPhone(phone)){


showError(
"signup-phone",
"Enter a valid phone number"
);


valid=false;


}

else{


clearError(
"signup-phone"
);


}







// Gender check

if(gender===""){


showError(
"signup-gender",
"Select your gender"
);


valid=false;


}

else{


clearError(
"signup-gender"
);


}






// Successful registration

if(valid){


let success =
document.getElementById(
"signup-success"
);



success.innerHTML =

"🍔 Welcome <strong>"
+
name
+
"</strong>! Your QuickBite account has been created successfully.";



success.classList.add("show");



form.reset();



setTimeout(function(){


closeModal(
"signup-modal"
);


success.classList.remove("show");


},3000);



}




});


}



});
