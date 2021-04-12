//create a back to top button when navbar is out of frame
const nav = document.querySelector("header>nav");
const header = document.querySelector("header");
let height = header.clientHeight - nav.clientHeight;
if(height === 0)
	height = nav.clientHeight;
let btn = document.createElement("button");
btn.innerText = "^";
btn.id = "btnFade";
btn.classList.add("btn", "btn-secondary", "btn-lg");
//places button in bottom right corner
btn.style.position = "fixed";
btn.style.bottom = "5px";
btn.style.right = "5px";
//add a timer that every 5 seconds the to top button will 'fade' out
setInterval(function() {
	//if the button is still visible fade it out some more
	if(btn.style.opacity > 0)
		btn.style.opacity = btn.style.opacity - .05 + "";
}, 1000);
window.addEventListener("scroll", function() {
	if(window.scrollY >= height)
	{
		//check if the button has already been added to DOM
		if(!document.querySelector("#btnFade"))
		{
			document.querySelector("body").appendChild(btn);
			//add onclick to button to scroll to top of the page
			document.querySelector("body").querySelector("#btnFade").addEventListener("click", function() {
					window.scroll(0,0);
				console.log("afbusdvcis");
			});
		}
		btn.style.opacity = "1";
	}
	else
	{
		//remove button if it's on the page, suppresses js errors in console
		if(document.querySelector("#btnFade"))
			document.querySelector("body").removeChild(btn);
	}
});