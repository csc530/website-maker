// delete confirmation to be used w/any delete link/button
function confirmDelete() {
	return confirm('Are you sure you want to delete this?');
}

/*****************************************************************************************************************************************/
//hidden element from super-user.php acting as the confirm password on register page
const hiddenPass = document.querySelector('form[action="register.php"] input[name="confirm-password"][type="hidden"]')

//used fill in confirm password
// on the super user form to add a new registered user
if(hiddenPass) {
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
	let elements = [body, main, footer, header];
	for(let i = 0; i < elements.length; i++) {
		//swap class attribute light with dark
		//if statement checks what class attribute they contain and swap accordingly
		if(elements[i].classList.contains("bg-light"))
			elements[i].classList.replace("bg-light", "bg-dark");
		else
			elements[i].classList.replace("bg-dark", "bg-light");
	}
});