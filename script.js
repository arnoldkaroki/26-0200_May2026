function openModal(id){
document.getElementById(id).classList.add("active");
}

function closeModal(id){
document.getElementById(id).classList.remove("active");
}


/* Promo sound */
function playPromoSound(){

try{

const AudioContext=window.AudioContext||window.webkitAudioContext;

if(!AudioContext)return;

const ctx=new AudioContext();

if(ctx.state==="suspended"){
ctx.resume();
}

let notes=[
{freq:880,start:0,duration:.18},
{freq:1175,start:.16,duration:.30}
];


notes.forEach(note=>{

let osc=ctx.createOscillator();
let gain=ctx.createGain();

osc.type="sine";
osc.frequency.value=note.freq;

let time=ctx.currentTime+note.start;

gain.gain.setValueAtTime(.0001,time);
gain.gain.exponentialRampToValueAtTime(.25,time+.02);
gain.gain.exponentialRampToValueAtTime(.0001,time+note.duration);

osc.connect(gain);
gain.connect(ctx.destination);

osc.start(time);
osc.stop(time+note.duration+.02);

});

}catch(error){

console.log("Audio unavailable");

}

}



/* Show promotions */

function showPromo(){

openModal("promo-modal");
playPromoSound();

}



/* Automatic welcome popup */

window.addEventListener("load",function(){


setTimeout(function(){

openModal("welcome-modal");

},1500);



setTimeout(function(){


let welcome=document.getElementById("welcome-modal");


if(!welcome.classList.contains("active")){

showPromo();

}

else{

setTimeout(function(){

showPromo();

},3000);

}


},8000);



});





/* Close popup when clicking outside */

document.addEventListener("DOMContentLoaded",function(){


document.querySelectorAll(".modal-overlay").forEach(function(modal){


modal.addEventListener("click",function(e){


if(e.target===modal){

modal.classList.remove("active");

}


});


});


});





/* Escape key closes popup */

document.addEventListener("keydown",function(e){


if(e.key==="Escape"){


document.querySelectorAll(".modal-overlay.active")
.forEach(function(modal){

modal.classList.remove("active");

});


}


});





/* ==============================
   FORM VALIDATION
============================== */



function isValidEmail(email){

let pattern=/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

return pattern.test(email);

}



function isValidName(name){

let pattern=/^[a-zA-Z\s]{2,}$/;

return pattern.test(name.trim());

}



function isValidPhone(phone){

let pattern=/^\+?[0-9]{10,13}$/;

return pattern.test(phone.replace(/\s/g,""));

}




function showError(id,message){

let field=document.getElementById(id);

let error=document.getElementById(id+"-error");


field.classList.add("invalid");

error.textContent=message;

error.style.display="block";

}



function clearError(id){

let field=document.getElementById(id);

let error=document.getElementById(id+"-error");


field.classList.remove("invalid");

error.textContent="";

error.style.display="none";

}





document.addEventListener("DOMContentLoaded",function(){



let email=document.getElementById("signup-email");


if(email){

email.addEventListener("blur",function(){


if(email.value===""){

clearError("signup-email");

}

else if(!isValidEmail(email.value.trim())){

showError(
"signup-email",
"Enter a valid email e.g. stacey20@gmail.com"
);

}

else{

clearError("signup-email");

}


});


}





let form=document.getElementById("signup-form");



if(form){


form.addEventListener("submit",function(e){


e.preventDefault();



let name=document.getElementById("signup-name").value.trim();

let email=document.getElementById("signup-email").value.trim();

let phone=document.getElementById("signup-phone").value.trim();

let gender=document.getElementById("signup-gender").value;



let valid=true;



if(!isValidName(name)){

showError(
"signup-name",
"Enter a valid name"
);

valid=false;

}

else{

clearError("signup-name");

}





if(!isValidEmail(email)){


showError(
"signup-email",
"Enter a valid email e.g. stacey20@gmail.com"
);


valid=false;


}

else{

clearError("signup-email");

}





if(!isValidPhone(phone)){


showError(
"signup-phone",
"Enter a valid phone number"
);


valid=false;


}

else{

clearError("signup-phone");

}





if(gender===""){


showError(
"signup-gender",
"Please select gender"
);


valid=false;


}

else{

clearError("signup-gender");

}







if(valid){


let success=document.getElementById("signup-success");


success.innerHTML=
"🍔 Welcome <strong>"+name+
"</strong>! Your QuickBite account has been created successfully.";


success.classList.add("show");



form.reset();



setTimeout(function(){


closeModal("signup-modal");

success.classList.remove("show");


},3000);



}



});


}



});
