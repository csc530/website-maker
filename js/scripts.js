// delete confirmation to be used w/any delete link/button
function confirmDelete() {
    return confirm('Are you sure you want to delete this?');
}

/*****************************************************************************************************************************************/
//hidden element from super-user.php acting as the confirm password on register page
const hiddenPass = document.querySelector('form[action="register.php"] input[name="confirm-password"][type="hidden"]')

//used fill in confirm password
// on the super user form to add a new registered user
if (hiddenPass) {
    //no need to be specific as we are in super-user.php page
    const pass = document.getElementById("password");
    //add event when form is submitted
    //input event for changes in an input element
    pass.addEventListener("input", function () {
        //copy password to hidden input
        hiddenPass.value = pass.value;
        console.log(hiddenPass.value);
    })
}