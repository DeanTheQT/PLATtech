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

function toggleDropdown() {
    document.getElementById("dropdown-menu").classList.toggle("show");
}
window.onclick = function(event) {
    // If the click target is NOT the menu button or the dropdown menu, hide the dropdown
    var menuBtn = document.getElementById("menu-btn");
    var dropdown = document.getElementById("dropdown-menu");
    if (event.target !== menuBtn && !dropdown.contains(event.target)) {
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
}
