// specify active menu
let pathArray=window.location.pathname.split("/");
let activeNavItem=pathArray[2];
if (!activeNavItem){
    document.getElementById("home").classList.add("current");
}else if (activeNavItem==='portfolio' ){
    console.log(activeNavItem);
    document.getElementById("my-portfolio").classList.add("current");
}else {
    document.getElementById(activeNavItem).classList.add("current");
}

// select dom elements
function $(el,getValue) {
    element="";
    if (getValue===undefined){
        getValue=false;
    }
    if (getValue){
        element=document.querySelector(el).value;
    }else {
        element=document.querySelector(el);
    }
    return element;
}
function $$(el) {
    element="";
    element=document.querySelectorAll(el);

    return element;
}
const menu_btn=$(".menu-btn");
const menu=$(".menu");
const menu_brand=$(".menu-brand");
const navbar=$(".navbar");
const nav_items=$$(".nav-item");

// set initial state of menu
var showMenu=false;
menu_btn.addEventListener("click",toggleMenu);
function toggleMenu() {
    if (!showMenu){
        menu_btn.classList.add("close");
        menu.classList.add("show");
        navbar.classList.add("show");
        menu_brand.classList.add("show");
        nav_items.forEach(item=>item.classList.add("show"));
        // set manu state
        showMenu=true;
    }else {
        menu_btn.classList.remove("close");
        menu.classList.remove("show");
        navbar.classList.remove("show");
        menu_brand.classList.remove("show");
        nav_items.forEach(item=>item.classList.remove("show"));
        // set manu state
        showMenu=false;
    }
}