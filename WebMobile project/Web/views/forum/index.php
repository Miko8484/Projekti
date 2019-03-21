<script>
$("#forum_add_button").show();
$("#forum_search_form").show();
$("#hot_topic").show();
document.getElementById("main_content_header").innerHTML="Forum";
</script>

<script>
$(document).ready(function() {
		//document.getElementById("tbody").innerHTML = "";
		url="api.php/forum/"+"";
		$.ajax({
		type: "GET",
		url: url,
		dataType: "json",
		contentType: "application/json; charset=utf-8",
		success: function (data) 
			{
				$.each(data,function(i,v){
						url1="api.php/fcomment/number/"+data[i].id;
						$.ajax({
							type: "GET",
							url:url1,
							dataType: "json",
							contentType: "application/json; charset=utf-8",
							success: function (data1)
							{
								var node = document.querySelector("#tbody");
								row = document.createElement("tr");
								row.className = "table-row";
								row.id = "id-row";
								row.style.cssText = "cursor:pointer";
								row.onclick = function() { document.location.href='index.php?controller=forum&action=about_topic&id='+data[i].id };
								document.getElementById('tbody').appendChild(row);
								
								ele = document.createElement("td");
								ele.style.cssText="width:30%";
								ele.innerHTML = data[i].topic_name;
								row.appendChild(ele);
							
								ele = document.createElement("td");
								ele.style.cssText="width:30%";
								ele.innerHTML = data1.number;
								row.appendChild(ele);
								
								ele = document.createElement("td");
								ele.style.cssText="width:10%";
								ele.innerHTML = data[i].views;
								row.appendChild(ele);
								
								ele = document.createElement("td");
								ele.style.cssText="width:10%";
								ele.innerHTML = data[i].post_date;
								row.appendChild(ele);
							}
						});
				 }); 
			}
		});
	
	
	$("#submit_button_search").click(function(){
		document.getElementById("tbody").innerHTML = "";
		url="api.php/forum/"+$("#pat").val();
		$.ajax({
		type: "GET",
		url: url,
		dataType: "json",
		contentType: "application/json; charset=utf-8",
		success: function (data) 
			{
				$.each(data,function(i,v){
						url1="api.php/fcomment/number/"+data[i].id;
						$.ajax({
							type: "GET",
							url:url1,
							dataType: "json",
							contentType: "application/json; charset=utf-8",
							success: function (data1)
							{
								var node = document.querySelector("#tbody");
								row = document.createElement("tr");
								row.className = "table-row";
								row.id = "id-row";
								row.style.cssText = "cursor:pointer";
								row.onclick = function() { document.location.href='index.php?controller=forum&action=about_topic&id='+data[i].id };
								document.getElementById('tbody').appendChild(row);
								
								ele = document.createElement("td");
								ele.style.cssText="width:30%";
								ele.innerHTML = data[i].topic_name;
								row.appendChild(ele);
							
								ele = document.createElement("td");
								ele.style.cssText="width:30%";
								ele.innerHTML = data1.number;
								row.appendChild(ele);
								
								ele = document.createElement("td");
								ele.style.cssText="width:10%";
								ele.innerHTML = data[i].views;
								row.appendChild(ele);
								
								ele = document.createElement("td");
								ele.style.cssText="width:10%";
								ele.innerHTML = data[i].post_date;
								row.appendChild(ele);
							}
						});
				 }); 
			}
		});
	});
});
</script>


<div class="table-responsive">          
  <table class="table" style="width:99%">
    <thead id="table-head">
      <tr>
        <th>Topic</th>
        <th>Comments</th>
        <th>Views</th>
        <th>Posted</th>
      </tr>
    </thead>
    <tbody id="tbody">
	
</tbody>
</table>
</div>