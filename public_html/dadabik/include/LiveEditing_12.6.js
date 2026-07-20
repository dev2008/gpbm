/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/

function LiveEditing() {
    let editingInProgress = false;
    let $tableSelector = null;
    let cellEditRequestCallback = null;
    let saveDataCallback = null;
    let cancelEditCallback = null;

    function init(options) {
        if (!options.selector) {
            throw 'Occorre specificare il selettore per la tabella';
        }
        if (!options.onCellEditRequest) {
            throw 'Occorre definire la funzione per acquisire il lock';
        }
        if (!options.onSave) {
            throw 'Occorre definire la funzione per salvare il dato';
        }
        if (!options.onCancel) {
            throw 'Occorre definire la funzione per l\'annullamento del live edit';
        }
        
        $tableSelector = $(options.selector);

        cellEditRequestCallback = options.onCellEditRequest;
        saveDataCallback = options.onSave;
        cancelEditCallback = options.onCancel;
        
        setupBindings();
    }

    function setupBindings() { 
        $tableSelector.on('dblclick', '[data-live-edit]', startLiveEditing);
        $tableSelector.on('keydown', '[data-live-edit][data-is-editing]', handleKeydown);
    }
    
    function liveEdit(cell,$tableCell){
    
        cellEditRequestCallback(cell)
        .then((answer) => {
            if (answer['result'] === 'done'){
                if ($tableCell.attr('data-is-editing') !== null) {
                    turnCellToLiveEditingCell(cell, answer['html_live_edit']);
                }
            }
            else{
                $tableCell.append($('<div data-error-on-lock class="live_edit_error_message">' + answer['error_message'] + '</div>'));
            }

        })
        .catch((errMessage) => {    
            $tableCell.append($('<div data-error-on-lock class="live_edit_error_message">' + errMessage + '</div>'));
        });
    }

    function startLiveEditing(event) {
    
                 
        if (editingInProgress) {
            return;
        }
        
        cleanCellErrorMessages();
        anyCellToCancel = 0;
        $tableSelector.find('[data-is-editing]').each(function (index) {
            //restoreCellToBasicTableCell(this);
            anyCellToCancel = 1;
            cancelEditing(this)
                .then((answer) => {
                    unlockCellInput(this);
                    restoreCellToBasicTableCell(this, answer['current_value']);
                    
                    // duplicazione
                    // Chiamiamo la richiesta di editing e trasformiamo la cella o
                    // emettiamo un messaggio di errore all'utente
                    let cell = event.target.closest('.results_grid_cell');
                    let $tableCell = $(cell);
                    liveEdit(cell,$tableCell);
                    
                    return false; // exit loop, I assume there is only one open live edit
                    
                })
                .catch(err => {
                    unlockCellInput(this);
                    $(this).append($('<div data-error-on-save class="text-xs text-danger">' + err + '</div>'));
                });
        });
        
        if (anyCellToCancel === 0){
            // duplicazione
            // Chiamiamo la richiesta di editing e trasformiamo la cella o
            // emettiamo un messaggio di errore all'utente
            let cell = event.target.closest('.results_grid_cell');
            let $tableCell = $(cell);
            liveEdit(cell,$tableCell);
        
        }
        

        
    }

    function handleKeydown(event) {
        if (event.originalEvent.code === 'Enter' && event.altKey) {
            event.target.value += '\n';
            return;
        }
        // CANCEL
        //let tableCell = event.target.parentElement;
        let tableCell = event.target.closest('td');
        if (event.originalEvent.code === 'Escape') {
            event.preventDefault();
            cancelEditing(tableCell)
                .then((answer) => {
                    unlockCellInput(tableCell);
                    restoreCellToBasicTableCell(tableCell, answer['current_value']);
                })
                .catch(err => {
                    unlockCellInput(tableCell);
                    $(tableCell).append($('<div data-error-on-save class="text-xs text-danger">' + err + '</div>'));
                });
            return;
        }

        // SAVE
        if (event.originalEvent.code === 'Enter') {
            event.preventDefault();
            // save cell data
            saveCellData(tableCell)
                .then((answer) => {
                    unlockCellInput(tableCell);
                    if (answer['result'] === 'done'){
                        restoreCellToBasicTableCell(tableCell, answer['new_value']);
                    }
                    else{
                        $(tableCell).append($('<div data-error-on-save class="live_edit_error_message">' + answer['error_message'] + '</div>'));
                    }
                })
                .catch((errMessage) => {
                    unlockCellInput(tableCell);
                    $(tableCell).append($('<div data-error-on-save class="live_edit_error_message">' + errMessage + '</div>'));
                });
            return;
        }
    }

    function saveCellData(tableCell) {
        cleanCellErrorMessages();
        lockCellInput(tableCell);
        return saveDataCallback(tableCell);
    }

    function cancelEditing(tableCell) {
        cleanCellErrorMessages();
        lockCellInput(tableCell, 'Cancelling');
        return cancelEditCallback(tableCell);
    }

    function lockCellInput(tableCell, message = 'Saving') {
        editingInProgress = true;
        $(tableCell).find('textarea').attr('disabled', true);
        $(tableCell).find('input').attr('disabled', true);
        $(tableCell).append($(`<div data-loading>${message}</div>`));
    }

    function unlockCellInput(tableCell) {
        editingInProgress = false;
        $(tableCell).find('textarea').attr('disabled', false);
        $(tableCell).find('input').attr('disabled', false);
        $tableSelector.find('[data-loading]').remove();
    }

    function cleanCellErrorMessages() {
        $tableSelector.find('[data-error-on-lock]').remove();
        $tableSelector.find('[data-error-on-save]').remove();
    }

    function turnCellToLiveEditingCell(cell, html) {
        if (editingInProgress) {
            return;
        }

        // Creazione della cella
        let $cell = $(cell);
        $cell.attr('data-is-editing', 1);
        $cell.html(html);

        let $formField = null;

        let $textarea = $cell.find('textarea');
        if ($textarea.length > 0) {
            $formField = $textarea;
        }

        let $textfield = $cell.find('input');
        if ($textfield.length > 0) {
            $formField = $textfield;
        }

        if ($formField) {
            // focus + cursor positioning 
            $formField.focus();
            if ($formField[0].type !== 'hidden'){ // eu edit, it's hidden for flatpckr fields and setSelectionRange throws an error
                $formField[0].setSelectionRange($formField.val().length, $formField.val().length);
            }
        }
    }

    function restoreCellToBasicTableCell(cell, html = null) {
        // eu edit
        /*const value = $(cell).attr('data-text');
        $(cell).attr('data-is-editing', null);
        $(cell).html(html || value);
        */
        
        $(cell).attr('data-is-editing', null);
        $(cell).html(html);
        
        
        
        
    }

    return {
        init
    }
}
