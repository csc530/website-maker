//create a back to top button when navbar is out of frame
const nav = document.querySelector("header>h1+nav");
const header = document.querySelector("header");
let height = header.clientHeight - nav.clientHeight;
window.addEventListener("scroll", function() {
	if(window.scrollY >= height)
		nav.classList.add('sticky');
	else
		nav.classList.remove('sticky');
});