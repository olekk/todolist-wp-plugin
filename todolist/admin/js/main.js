class Row {
	constructor(id, status, content) {
		this.id = Number(id);
		this.status = status==false ? false : true;
		this.content = content;
	}
}

//Globalne zmienne przechowujące dane do wyświetlenia

let rowList = [];
const firstRow = new Row(0, false, "Enter new task here...");

//Funkcja przyjmująca odpowiedź z serwa przez AJAX

function parseDB(res) {
	rowList=[firstRow, ...JSON.parse(res).map(ob=>new Row(ob.id, ob.status, ob.content))];
	render_mylist();
}

jQuery.post(todolist_ajax_url, "action=update_tasks", res => parseDB(res))

function render_mylist() {

	//Funcja tworząca jeden blok "row" - zgodnie z metodologią BEM

	let renderRow = (row) => {
		return renderEl("div", "row", [
			renderEl("div", "row__status", [
				renderEl("input", null, [], [{
					a: "tp",
					v: "checkbox",
					id: row.id
				}, {
					a: "ck",
					v: row.status
				}])
			]),
			renderEl("div", "row__content", [
				renderEl("input", null, [], [{
						a: "tp",
						v: "text",
						id: row.id
					}, {
						a: row.id ? "va" : "ph",
						v: row.content
					}
				])
			], [{
				a: "tt",
				v: "Click to edit"
			}]),
			renderEl("div", "row__act", 
				row.id ?
				[renderEl("span", "dashicons dashicons-trash", [], [{
					a: 'tt',
					v: 'Delete',
					id: row.id
				}]),
				renderEl("span", "dashicons dashicons-edit row_hidden", [], [{
					a: 'tt',
					v: 'Save',
					id: row.id
				}]),
				renderEl("span", "dashicons dashicons-dismiss row_hidden", [], [{
					a: 'tt',
					v: 'Cancel',
					id: row.id
				}])]
				 :
				[renderEl("span", "dashicons dashicons-plus row_hidden", [], [{
					a: 'tt',
					v: 'Add Task',
					id: row.id
				}]),
				renderEl("span", "dashicons dashicons-dismiss row_hidden", [], [{
					a: 'tt',
					v: 'Cancel',
					id: row.id
				}])]
			)
		]);
	}

	// Funkcja tworząca dowolny tag HTML. Poprzez zagnieżdżanie własnych instancji, mogę łatwo napisać drzewo dokumentu HTML

	let renderEl = (tag, classList, children = [], moreAttrs = []) => {
		let el = document.createElement(tag);
		if (classList != null) el.classList = classList;
		moreAttrs.map(attr => {
			switch (attr.a) {
				case 'ph':
					el.placeholder = attr.v;
					break;
				case 'tp':
					el.type = attr.v;
					break;
				case 'tt':
					el.title = attr.v;
					break;
				case 'ck':
					el.checked = attr.v;
					break;
				case 'va':
					el.value = attr.v;
					break;
			}
			if(attr.hasOwnProperty('id')) switch(attr.v) {
				case 'Delete':
					el.addEventListener("click", ()=>deletetask(attr.id))
				break;
				case 'Save':
					el.addEventListener("click", ()=>saveChanges(attr.id))
				break;
				case 'Add Task':
					el.addEventListener("click", ()=>addTask())
				break;
				case 'Cancel':
					el.addEventListener("click", ()=>cancelEdit(attr.id))
				break;
				case 'text':
					el.addEventListener("keydown", e=>{
						if(e.keyCode === 13)
							saveChanges(attr.id)})
				break;
				case 'checkbox':
					el.addEventListener("click", ()=>{toggleCheckbox(attr.id)})
				break;
			}
		})
		children.forEach(child => el.appendChild(child));
		return el;
	}

	// Funkcje obsługujące zmiany w todoliscie, wysyłając zapytania AJAX na bierząco.

	function addTask() {
		let content = inVal(0);
			if (content.length > 0) {
				jQuery.post(todolist_ajax_url, "content=" + content + "&param=addtask&action=update_tasks", res => parseDB(res))

			} else {
				alert("Can't save empty note!");
			}
			cancelEdit(0);
	}

	function saveChanges(_id) {
		let content = inVal(_id);
			if (content.length > 0) {
				jQuery.post(todolist_ajax_url, "id="+_id+"&content=" + content + "&param=savechanges&action=update_tasks", res => parseDB(res));
			} else {
				cancelEdit(_id);
				alert("Can't save empty note!");
			}
	}

	function toggleCheckbox(_id) {
		let status = inVal(_id, null, true) ? 1 : 0;
		jQuery.post(todolist_ajax_url, "id="+_id+"&status=" + status + "&param=togglecheckbox&action=update_tasks", res => parseDB(res))
	}

	function deletetask(_id) {
		jQuery.post(todolist_ajax_url, "id="+_id+"&param=deletetask&action=update_tasks", res => parseDB(res))

	}

	// Funkcje pomocnicze, których nie musiałbym tworzyć, gdybym lepiej rozplanował data flow
	
	function cancelEdit(_id) {
		inVal(_id, _id ? rowList.filter(row=>row.id==_id)[0].content : "");
	}

	function inVal(_id, newValue=null, b=false) {
		let blok = rowList.filter(row=>row.id==_id)[0];
		if(newValue!=null) blok.element.children[1].children[0].value=newValue;
		return b ? blok.element.children[0].children[0].checked : blok.element.children[1].children[0].value; 
	}

	// Reset i wstawienie gotowych elementów na stronę

	document.getElementsByClassName("mylist")[0].innerHTML="";
	rowList.map(row => {
		row.element = renderRow(row);
		document.getElementsByClassName("mylist")[0].appendChild(row.element);
	});

	// Po tym wywołuję funkcję wykorzystującą jQuery odpowiadającą za wizualne interakcje

	jq(jQuery);
}


function jq ($) {
	'use strict';

	$(".row").hover(
		function () {
			$(this).addClass("row_hover");
		},
		function () {
			$(this).removeClass("row_hover");
		}
	);

	$(".row__content").children("input").keyup(function (e) {
		let row = $(this).parent().parent();
		if (e.which===13) hideEditButtons(row);
		else showEditButtons(row);
	});

	function showEditButtons(row) {
		row.children(".row__act").children('[title=Delete]').addClass("row_hidden");
		row.children(".row__act").children('[title=Save]').removeClass("row_hidden").click(function () {
			hideEditButtons(row)
		});
		row.children(".row__act").children('[title=Cancel]').removeClass("row_hidden").click(function () {
			hideEditButtons(row)
		});
		row.children(".row__act").children('[title~=Add]').removeClass("row_hidden").click(function () {
			hideEditButtons(row)
		});
	}

	function hideEditButtons(row) {
		row.children(".row__act").children('[title=Delete]').removeClass("row_hidden")
		row.children(".row__act").children('[title=Save]').addClass("row_hidden")
		row.children(".row__act").children('[title=Cancel]').addClass("row_hidden")
		row.children(".row__act").children('[title~=Add]').addClass("row_hidden")
	}
}
