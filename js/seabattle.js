window.onload = function() {
	var table = document.getElementById('SetShipField');
	var txt = document.getElementById('decks');
	var a = document.getElementById('btn');
	var t = "";
	var arr = new Map();
	for (var i = 0, row; row = table.rows[i]; i++) {
   
		for (var j = 0, col; col = row.cells[j]; j++) {
			if(col.id == ""){
				continue;
			}
			document.getElementById(col.id).onclick = function(e) {
			//txt.value = e.target.id;
				if(!arr.has(e.target.id)){
					e.target.style.background = "RED";
					arr.set(e.target.id,'deck');
				}else{
					e.target.style.background = "Aqua";
					arr.delete(e.target.id);
				}
				var m = '';
				arr.forEach(function(value,key) {
					m = m + key + ' ';//'key = ' + key +', value = ' + value;    
				});
				txt.value = String(m);
			
			}

		}  
	}
	// a.onclick = function() {
        // var m = "";
		// arr.forEach(function(value,key) {
			// m = m + key;//'key = ' + key +', value = ' + value;    
		// });
		// txt.value = m;
		// document.location.href = "index.php?action='createPlayer'";
		
    // }
	
}

  //var countSpan = elem.childNodes.length - 1;
  
