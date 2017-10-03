/**
 * Allow sorting tables
 * BASE: http://stackoverflow.com/questions/3160277/jquery-table-sort
 */
$('th').on('click', function() {
    var table = $(this).parents('table').eq(0)
    var rows = table.find('tr:gt(0)').toArray().sort(tableComparer($(this).index()))
    this.asc = !this.asc
    if (!this.asc){rows = rows.reverse()}
    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
})

/**
 * Function that compares table cell values.
 * BASE: http://stackoverflow.com/questions/3160277/jquery-table-sort
 */
function tableComparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
    }
}

/**
 * Function that gives Value of table cell.
 */
function getCellValue(row, index){ return $(row).children('td').eq(index).text() }


/**
 * Add active class.
 */
$('a[href="' + this.location.pathname + '"]').parents('li').addClass('active');


/**
 * Common function that searches specified table by the value of given input.
 * All rows that dont match the search, will be hidden.
 */
function searchTable(inputId, tableId) {
	// First declare and fill needed variables.
	var filter, table, tr, td, i;
	input = document.getElementById(inputId);
	filter = input.value.toUpperCase();
	table = document.getElementById(tableId);
	tr = table.getElementsByTagName("tr");

	// Then loop the table and hide ones that dont match.
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[0];
		if (td) { //Found TD.
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = ""; //Match found!
			} else {
				tr[i].style.display = "none"; //Doesn't match.
			}
		}
	}
}

/**
 * selects all options from specified select
 */
function selectAllOptions(selectName) { 
	selectBox = document.getElementById(selectName);
	for (var i = 0; i < selectBox.options.length; i++) { 
		selectBox.options[i].selected = true; 
	} 
}

/**
 * Asks confirmation before actually deleting.
 * Checks all forms who have been marked with class deleteForm.
 */
$('form.deleteForm').on('submit', function() {
	var doDelete = confirm("Are you sure that you want to delete?");
    return doDelete;
});