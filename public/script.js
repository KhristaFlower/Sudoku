/**
 * Validate key down events when typing into a Sudoku cell input field.
 *
 * @param event The key down event.
 * @returns {boolean} False if we don't want to enter the pressed value.
 */
function validate(event)
{
    var target = event.target || event.srcElement;
    var theEvent = event || window.event;
    var keyCode = theEvent.keyCode || theEvent.which;

    var cellIndex = target.getAttribute('data-cell-index');

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

        // We might be trying to do something other than putting in a number.
        // C will clear all pencil numbers from the cell.
        if (key === 'c') {
            clearPencilsFromIndex(cellIndex);
        }

        theEvent.returnValue = false;
        if(theEvent.preventDefault) {
            theEvent.preventDefault();
        }
        return false;
    }

    var pencilCheckbox = document.getElementById('pencil');

    // If the pencil mode is active, place a pencil value.
    // On macOS CTRL + NUM can be used to toggle pencil values. // TODO: Windows / Linux compatible quick shortcut.
    if (pencilCheckbox.checked || theEvent.ctrlKey) {
        togglePencil(cellIndex, key);

        // We don't want to actually put the number into the box if we are in pencil mode - stop the event.
        theEvent.returnValue = false;
        if (theEvent.preventDefault) {
            theEvent.preventDefault();
        }
        return false;
    }
}

/**
 * Remove all pencil values from a cell at the index cellIndex.
 *
 * @param cellIndex The index of the cell to have its pencil values cleared.
 */
function clearPencilsFromIndex(cellIndex)
{
    // Remove all visual elements from the grid.
    var pencilGrid = document.getElementById('pencil-grid-' + cellIndex);
    pencilGrid.innerHTML = '';

    // Remove the data from the input so we don't post the pencil values back when validating.
    var pencilInputElement = document.getElementById('pencil-input-' + cellIndex);
    pencilInputElement.value = '';
}

/**
 * Toggle the pencil value for the cell at cellIndex.
 *
 * @param cellIndex The index of the cell.
 * @param value The number we need to toggle.
 */
function togglePencil(cellIndex, value)
{
    // Find the input we need to modify to store the change. We need to update this input
    // so when the puzzle is validated again the pencil values are preserved.
    var pencilInputElement = document.getElementById('pencil-input-' + cellIndex);

    var pencilValue = pencilInputElement.value;
    var pencilValues = [];

    if (pencilValue.length > 0) {
        pencilValues = pencilValue.split(';');
    }

    if (pencilValues.indexOf(value) >= 0) {
        // The pencil value already exists, lets remove it.
        pencilValues.splice(pencilValues.indexOf(value), 1);

        // Lets remove the element that shows the pencil value from the page.
        var pencilCellValueToRemove = document.getElementById('pencil-cell-' + cellIndex + '-' + value);

        pencilCellValueToRemove.parentNode.removeChild(pencilCellValueToRemove);
    } else {
        // The pencil value doesn't exist, add it.
        pencilValues.push(value);

        // Add the number to the grid so it can be seen.
        var pencilCellValueToAdd = document.createElement('div');
        pencilCellValueToAdd.classList.add('pencil-square', 'pencil-square-' + value);
        pencilCellValueToAdd.id = 'pencil-cell-' + cellIndex + '-' + value;
        pencilCellValueToAdd.textContent = value;

        // Add the DOM element containing our pencil number to the pencil grid.
        var pencilGrid = document.getElementById('pencil-grid-' + cellIndex);
        pencilGrid.appendChild(pencilCellValueToAdd);
    }

    // Write the new values back to the input so that they appear on the page after validation.
    pencilInputElement.value = pencilValues.join(';');
}

/**
 * Add a global key listener to the page that allows for changing various settings using shortcuts.
 */
window.addEventListener('keypress', function (event)
{
    var theEvent = event || window.event;
    var keyCode = theEvent.keyCode || theEvent.which;
    var key = String.fromCharCode(keyCode);

    if (key === 'p') {
        togglePencilMode();
    }

});

/**
 * Toggle the pencil mode setting.
 */
function togglePencilMode()
{
    // Update the pencil mode setting checkbox.
    var pencilCheckbox = document.getElementById('pencil');

    pencilCheckbox.checked = !pencilCheckbox.checked;
}
