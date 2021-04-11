// delete confirmation to be used w/any delete link/button
function confirmDelete() {
	return confirm('Are you sure you want to delete this?');
}

/*****************************************************************************************************************************************/
//hidden element from super-user.php acting as the confirm password on register page
const hiddenPass = document.querySelector('form[action="register.php"] input[name="confirm-password"][type="hidden"]')

//used fill in confirm password
// on the super user form to add a new registered user
if(hiddenPass)
{
	//no need to be specific as we are in super-user.php page
	const pass = document.getElementById("password");
	//add event when form is submitted
	//input event for changes in an input element
	pass.addEventListener("input", function() {
		//copy password to hidden input
		hiddenPass.value = pass.value;
		console.log(hiddenPass.value);
	})
}

/*****************************************************************************************************************************************/
//adds functionality to switch between light and dark mode/theme
//adds an onclick to the theme button (sun or moon icon)
const theme = document.getElementById("theme");
theme.addEventListener("click", function() {
	//get elements needed to change page's theme
	const body = document.querySelector("body");
	const main = document.querySelector("main");
	const footer = document.querySelector("footer");
	const header = document.querySelector("header");
	//add elements to array for easy change of theme
	let elements = [header, body, main, footer];
	for(let i = 0; i < elements.length; i++)
	{
		//swap class attribute light with dark
		//if statement checks what class attribute they contain and swap accordingly
		if(elements[i].classList.contains("bg-light"))
		{
			elements[i].classList.replace("bg-light", "bg-dark");
			//saves the selected theme to the session
			sessionStorage.setItem("theme", "dark");
			//switch displayed image
			theme.setAttribute("src", "../images/moon.svg");
			//change alt text appropriately
			theme.setAttribute("alt", "Picture of a crescent moon behind a cloud")
		}
		else
		{
			elements[i].classList.replace("bg-dark", "bg-light");
			sessionStorage.setItem("theme", "light");
			theme.setAttribute("src", "../images/sun.svg");
			theme.setAttribute("alt", "Picture of the sun in front of a cloud")

		}
	}
	console.log(sessionStorage.getItem("theme"));
});
//when the document is loaded check the theme and if (it's not the default) light 'click' the theme button to switch to dark, for continuity
if(sessionStorage.getItem("theme") === "dark")
	theme.click();

/*****************************************************************************************************************************************/
//Appends the form-label and form-control-lg class to appropriate elements opposed to hardcoding it everywhere everytime
let labels = document.querySelectorAll("form label");
let inputs = document.querySelectorAll("form input");
let ta = document.querySelectorAll("form textarea");
for(let i = 0; i < labels.length; i++)
	labels[i].classList.add("form-label");
for(let i = 0; i < inputs.length; i++)
	inputs[i].classList.add("form-control-lg");
for(let i = 0; i < ta.length; i++)
{
	ta[i].classList.add("form-control-lg");
	//makes textarea the same length/width of text type inputs
	ta[i].style.width = inputs[0].clientWidth + "px";
}

/*****************************************************************************************************************************************/
//Makes all form buttons large and btn class
let btns = document.querySelectorAll("form button");
for(let i = 0; i < btns.length; i++)
	btns[i].classList.add("btn-lg");

/*****************************************************************************************************************************************/
//Script to make live page when editing website's page
const root = document.querySelector("div.side-by-side form+div#shadowRoot");
let shadow = root.attachShadow({mode: "open"});
if(root)
{
	const pageTitle = document.querySelector("div.side-by-side form input#pageTitle");
	const pageContent = document.querySelector("div.side-by-side form textarea#pageContent");
	//add default page styles
	let element = document.createElement("link");
	element.rel = "stylesheet";
	element.href = "../css/userStyles.css";
	element.type = "text/css";
	shadow.appendChild(element);
	//add the rest of the basic elements to shadowDOM
	element = document.createElement("body");
	shadow.appendChild(element);

	element.appendChild(document.createElement("header"));
	element.querySelector("header").appendChild(document.createElement("h1"));

	element.appendChild(document.createElement("main"));
	element.querySelector("main").appendChild(document.createElement("p"));
	//Add shadow class to prevent the user styles making the preview too big
	shadow.querySelector("body").classList.add("shadow");
	shadow.querySelector("header").classList.add("shadow");
	shadow.querySelector("main").classList.add("shadow");
	//add the style to prevent a too large preview
	element = document.createElement("style");
	element.innerHTML = ".shadow{min-height: 100%;}";
	shadow.prepend(element);
	const rootTitle = shadow.querySelector("h1");
	const rootContent = shadow.querySelector("p");

	//function to edit the preview port with the new content in the inputs/textarea
	function update(event) {
		console.log(event.target.value);
		//check the name i.e. php post variable name/attr name
		if(event.target.name === "pageContent")
			rootContent.innerHTML = event.target.value;
		else
			rootTitle.innerHTML = event.target.value;
	}

	//places the update function on every keystroke in the textarea and input
	pageTitle.addEventListener("keyup", update);
	pageContent.addEventListener("keyup", update);
	//places default values of inputs into preview
	rootContent.innerHTML = pageContent.value;
	rootTitle.innerHTML = pageTitle.value;

}