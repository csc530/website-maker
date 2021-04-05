//Used to make the navbar sticky
// regular position sticky won't work because it's wrapped in header with h1
// (referring to user's site and I don't the title sticky as well)
const nav = document.querySelector("header>h1+nav");
const header = document.querySelector("header");
let height = header.clientHeight - nav.clientHeight;
window.addEventListener("scroll", function() {
	if(window.scrollY >= height)
		nav.classList.add('sticky');
	else
		nav.classList.remove('sticky');
});