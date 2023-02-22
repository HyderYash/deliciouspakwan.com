//COMMON API CALL 
var fetchAjaxData = function(ajaxPath, params='', method='POST'){	
	return new Promise(function(resolve){
		var xhr = new XMLHttpRequest();
		xhr.open(method, ajaxPath,true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		xhr.onreadystatechange = function () {
		  var DONE = 4; // readyState 4 means the request is done.
		  var OK = 200; // status 200 is a successful return.
		  if (xhr.readyState === DONE) {
			if (xhr.status === OK) {
			  resolve(xhr.responseText); // 'This is the returned text.'
			} else {
			  console.log('Error: ' + xhr.status); // An error occurred during the request.
			  alert('Error: ' + xhr.status);
			  resolve(xhr.status);
			}
		  }
		};			
	});
};
var fetchAPIData = function(apiPath, params='', method='POST'){	
	return new Promise(function(resolve){
		if(method === 'POST'){
			var para = {method: method,	redirect: "manual",	mode: "cors",	body: JSON.stringify(params),
				headers: {"Referer": window.location.href,"Content-Type": "application/json"}
			}
		}
		if(method === 'GET'){
			var para = {method: method,	redirect: "manual",	mode: "cors",
				headers: {"Referer": window.location.href}
			}
		}
		
		fetch(apiPath, para).then(function(response) {
			alert(JSON.stringify(response));
			if (response.status == 200) {
				response.json().then(function(json) {
					resolve(json);
				});
			}else if (response.status == 400) {
				response.json().then(function(json) {
					window.onerror(JSON.stringify(json));
					resolve(json);
				});	
			}else{
				window.onerror(JSON.stringify(response));
				resolve(response);
			}			
		});
	});
};

/*SET CURRENT THEME -- Strat */
function setCurrentTheme(themeid)
{
	var ajaxPath = "/ajax/set_current_theme";
	var params = "themeid=" + themeid;
	fetchAjaxData(ajaxPath, params).then(function(ajaxResponse) {
		location.href = ajaxResponse;
	});
	
}
/*SET CURRENT THEME -- End */
/*Exp_1... to Exp_5 Section processing -- Start*/
function edit_exp_row(ids,action_button_id, availableAmt)
{
	var id_array = ids.split(',');
	/*alert(document.getElementsByTagName('input').length);*/
	if(document.getElementsByTagName('input').length == 1)
	{
		document.getElementById('edit_button'+action_button_id).style.display="none";
		document.getElementById('save_button'+action_button_id).style.display="block";
		
		for (i = 0, len = id_array.length, text = ""; i < len; i++) 
		{ 
			if(document.getElementById(id_array[i]))
			{
				var existingVal=document.getElementById(id_array[i]).innerHTML;
				if(i == 0 && (existingVal == '' || existingVal == 0))
				{
					existingVal = availableAmt;
				}
				document.getElementById(id_array[i]).innerHTML="<input size='4' maxlength='10' type='text' id='" +id_array[i].replace('div_','')+"' name='" +id_array[i].replace('div_','')+"' value='"+existingVal+"' onkeyup='javascript:changeVal(this.value,this.id);'>";
			}
		}
	}
	else
	{
		alert('An another update is already in progress. Please do one udate at a time.');
		return false;
	}
}
function changeVal(ival,id)
{
	var new_ival = ival.replace(/[^0-9\.]/g,'');
	document.getElementById(id).value = new_ival;
	
}
function save_exp_row(ids)
{
	document.getElementById('exp_mod_form').submit();
}
function showThisMonthDomData(val,item)
{
	location.href = "/index.php/?" + item + "=Y&timeDuration=" + val;
}
function showThisMonthData(val)
{
	location.href = "/showMonthlyStatusCurrent/?showMonth=" + val;
}
function showThisYearData(val,path)
{
	location.href = "/" + path + "/?showItemYear=" + val;
}
/*Exp_1... to Exp_5 Section processing -- End*/


/*PAyDay Section processing -- Start*/
function edit_payday_row(id)
{
	//alert(document.getElementsByTagName('input').length);
	if(document.getElementsByTagName('input').length == 1)
	{	
		document.getElementById('edit_button_'+id).style.display="none";
		document.getElementById('save_button_'+id).style.display="block";
		var existingVal=document.getElementById(id).innerHTML;
		document.getElementById(id).innerHTML="<input size='3' maxlength='3' type='text' id='input_" +id+"' name='input_" +id+"' value='"+existingVal+"' onkeyup='javascript:changeVal(this.value,this.id);'>";
	}
	else
	{
		alert('An another update is already in progress. Please do one udate at a time.');
		return false;
	}	
}
function save_payday_row(id)
{
	var curr_payday = document.getElementById('input_' + id).value;
	var ajaxPath = "/ajax/modify_pay_day";
	var params = "item_id=" + id + "&curr_payday_val=" + curr_payday;
	fetchAjaxData(ajaxPath, params).then(function(ajaxResponse) {
		location.href = ajaxResponse;
	});	
}


/*PAyDay Section processing -- End*/

/*Allocated Amt Section processing -- Start*/
function edit_allocate_amt_row(id)
{
	if(document.getElementsByTagName('input').length == 1)
	{	
		document.getElementById('edit_button_'+id).style.display="none";
		document.getElementById('save_button_'+id).style.display="block";
		var existingVal=document.getElementById(id).innerHTML;
		document.getElementById(id).innerHTML="<input size='7' maxlength='10' type='text' id='input_" +id+"' name='input_" +id+"' value='"+existingVal+"' onkeyup='javascript:changeVal(this.value,this.id);'>";
	}
	else
	{
		alert('An another update is already in progress. Please do one udate at a time.');
		return false;
	}	
}
function save_allocate_amt_row(id)
{
	var allo_amt = document.getElementById('input_' + id).value;
	var ajaxPath = "/ajax/modify_allocation";
	var params = "allocation_id=" + id + "&allocation_amt=" + allo_amt;
	fetchAjaxData(ajaxPath, params).then(function(ajaxResponse) {
		location.href = ajaxResponse;
	});	
	
}
/*Allocated Amt Section processing -- End*/


/*Curr_Bal Amt Section processing -- Start*/
function edit_curr_bal_amt_row(id)
{
	if(document.getElementsByTagName('input').length == 0)
	{	
		document.getElementById('edit_button_'+id).style.display="none";
		document.getElementById('save_button_'+id).style.display="block";
		var existingVal=document.getElementById(id).innerHTML;
		document.getElementById(id).innerHTML="<input size='7' maxlength='10' type='text' id='input_" +id+"' name='input_" +id+"' value='"+existingVal+"' onkeyup='javascript:changeVal(this.value,this.id);'>";
	}
	else
	{
		alert('An another update is already in progress. Please do one udate at a time.');
		return false;
	}	
}
function save_curr_bal_amt_row(id)
{
	var curr_amt = document.getElementById('input_' + id).value;
	var ajaxPath = "/ajax/modify_current_bal";
	var params = "acct_id=" + id + "&curr_bal_amt=" + curr_amt;
	fetchAjaxData(ajaxPath, params).then(function(ajaxResponse) {
		location.href = ajaxResponse;
	});	
	
}


/*Curr_Bal Amt Section processing -- End*/

function delete_row(id)
{
 /*$.ajax
 ({
  type:'post',
  url:'modify_records.php',
  data:{
   delete_row:'delete_row',
   row_id:id,
  },
  success:function(response) {
   if(response=="success")
   {
    var row=document.getElementById("row"+id);
    row.parentNode.removeChild(row);
   }
  }
 });*/
 

	var ajaxPath = "modify_records.php";
	var params = "row_id=" + id + "&delete_row=delete_row";
	fetchAjaxData(ajaxPath, params).then(function(ajaxResponse) {
	   if(ajaxResponse=="success")
		{
			var row=document.getElementById("row"+id);
			row.parentNode.removeChild(row);
		}
	});	
}

function insert_row()
{
	var name=document.getElementById("new_name").value;
	var age=document.getElementById("new_age").value;
	var ajaxPath = "modify_records.php";
	var params = "name_val=" + name +  + "&age_val="+ age + "&insert_row=insert_row";
	fetchAjaxData(ajaxPath, params).then(function(ajaxResponse) {
	   if(ajaxResponse=="success")
		{
			var id=ajaxResponse;
			var table=document.getElementById("user_table");
			var table_len=(table.rows.length)-1;
			var row = table.insertRow(table_len).outerHTML="<tr id='row"+id+"'><td id='name_val"+id+"'>"+name+"</td><td id='age_val"+id+"'>"+age+"</td><td><input type='button' class='edit_button' id='edit_button"+id+"' value='edit' onclick='edit_row("+id+");'/><input type='button' class='save_button' id='save_button"+id+"' value='save' onclick='save_row("+id+");'/><input type='button' class='delete_button' id='delete_button"+id+"' value='delete' onclick='delete_row("+id+");'/></td></tr>";

			document.getElementById("new_name").value="";
			document.getElementById("new_age").value="";
		}
	});
}

