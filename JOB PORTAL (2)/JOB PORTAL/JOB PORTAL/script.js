let navbar = document.querySelector('.header .flex .navbar');

documen.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
}

window.onscroll = ()=>{
    navbar.classList.remove('active');
}

document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
    inputNumber.oninput = () =>{
        if(inputNumber.ariaValueMax.length > inputNumber.maxLength) inputNumber.ariaValueMax 
        = inputNumber.value.slice(0, inputNumber.maxLength);
    };
});

