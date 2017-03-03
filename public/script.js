function validate(evt) {

    var target = evt.target || evt.srcElement;
    var theEvent = evt || window.event;
    var keyCode = theEvent.keyCode || theEvent.which;

    // Prevent the enter key from submitting the form.
    if (keyCode === 13) {
        return false;
    }

    // If we pressed the escape key then we want to remove focus from the field.
    if (keyCode === 27) {
        target.blur();
    }

    // Check that the value of the key pressed is an allowed numerical one.
    var key = String.fromCharCode(keyCode);

    var permissibleCharacters = /[1-9]|\./;
    if(!permissibleCharacters.test(key)) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) {
            theEvent.preventDefault();
        }
        return false;
    }

    // Clear the input so we can overwrite it with the new value if there is one.
    target.value = '';

    // todo: pencil mode compatibility - this has problems on Windows systems.

    // If we hold CTRL then we insert the value as pencil.
    if (evt.ctrlKey) {
        // CTRL + KEY does not put the key into the input, so we do that here.
        target.value = key;
        target.classList.add('pencil');
    } else {
        target.classList.remove('pencil');
    }
}
