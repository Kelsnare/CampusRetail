var files;

function fillSell(){
	$("#contentOp").html("<span id='addProduct'><b>Add Product</b></span> | <span id='pOrders'><b>Check Pending Orders</b></span>");
	$("#sell").attr("class", "userHeaderOpS");
	$("#buy").attr("class", "userHeaderOp");
	$("#rightContent").load("scripts/userServ.php?fillSell="+category+"&uid="+uid);

}

function fillBuy(){
	$("#sell").attr("class", "userHeaderOp");
	$("#buy").attr("class", "userHeaderOpS");
	$("#contentOp").html("<span id='pOrdersBuy'><b>Check ordered products</b></span>");
	$("#rightContent").load("scripts/userServ.php?fillBuy="+category+"&uid="+uid);
}

function locate(){
	var width = $(window).width() - 230;
	if(width < 770)
		width = 770;
	var height = $(window).height();
	if(height < 400)
		height = 400;
	$("#right").css({
		"width" : width+"px",
		"height" : height+"px"
	});
}

function userHeaderOpClick(id){
	//var id = $(this).attr("id");
	var sid = $(".userHeaderOpS").attr("id");
	$("#"+sid).attr("class", "userHeaderOp");
	$("#"+id).attr("class", "userHeaderOpS");
	if(id == "sell"){
		load = "sell";
		fillSell();
	}else if(id == "buy"){
		load = "buy";
		fillBuy();
	}else if(id == "sold"){
		load="sold";

	}else if(id == "bought"){
		load = "bought";
	}
}

function categoryClick(id){
	var sid = $(".categoryS").attr("id");
	$("#"+sid).attr("class", "category");
	$("#"+id).attr("class", "categoryS");

	category = id;
	if(load == "sell"){
		fillSell();
	}else if(load == "buy"){
		fillBuy();
	}else if(load == "sold"){

	}else if(load == "bought"){

	}
}

function addProduct(){
	createAddProductTool();
}

function createAddProductTool(){
	createOverlay();
	$("#lightbox").append("<div id='addProductTool' style='position:absolute;height:500px;width:700px;border-radius:10;background-color:white;padding:10px;overflow-y:auto;'></div>");
	positionTool("addProductTool");
	positionCloseOverlay("addProductTool");

	var toFill = "<div id='pName'><input type='text' placeholder='Product Name' style='height: 30px;padding:5px;border-radius:3px;outline:none;border:1px solid grey;margin-top:7px;width:500px;'></div><div id='pCost'><input type='text' placeholder='Product Cost' style='height: 30px;padding:5px;border-radius:3px;outline:none;border:1px solid grey;margin-top:7px;width:500px;'></div><div id='pDesc'><textarea placeholder='Give a detailed description about your product' style='height: 200px;padding:5px;border-radius:3px;outline:none;resize:false;border:1px solid grey;margin-top:7px;width:500px;'></textarea></div><div id='pCategory'></div><div id='submitBtn'><button style='padding:5px;border-radius:3px;color:white;background-color:black;cursor:pointer;' onclick='saveProduct();'>Save</button></div>";
	$("#addProductTool").append(toFill);

	$.ajax({
		type: "GET",
		url: "scripts/userServ.php",
		data: {'getCategories' : 'all'},
		async: false,
		success: function(response){
			if(response.indexOf("ERROR") == -1){
				$("#pCategory").append(response);
			}
		}
	});

}

function saveProduct(){
	var pname = $("#pName>input").val();
	var pcost = $("#pCost>input").val();
	var pdesc = $("#pDesc>textarea").val();
	var cat = $("#pCategory>select").val();

	//alert(pname + " "+cat+ " " +pdesc);

	$("#submitBtn > button").hide();
	$("#errorStatus").remove();
	$.ajax({
		type: "POST",
		url: "scripts/userServ.php",
		data: {'saveProduct': pname, 'pcost' : pcost, 'pdesc' : pdesc, 'cat' : cat,'u' : user},
		async: false,
		success: function(response){
			if(response.indexOf("success") > -1){
				var arr = response.split('.');
				pictureUpload(arr[1]);
			}else{
				$("#submitBtn > button").fadeIn();
				$("#submitBtn").append("<br><span id='errorStatus' style='color:red'>Error saving product</span>");
			}
		}
	});
}

function pictureUpload(pid){
	var form = "<form id='prodPicForm' enctype='multipart/form-data' action = 'scripts/prodPicUpload.php?id="+pid+"' method='POST'><input type='file' name='avatar' style='border:none;padding: 5px; font-family:calibri;'><input type='submit' value='Upload' id='uploadBtn' style='border:none;box-shadow:0 0 3px rgba(0,0,0,0.8);border-radius: 3px;padding:5px;color:white;background-color:black;'></form>";
	$("#addProductTool").html(form);
}

function createOverlay(){
	$("body").append("<div id='lightbox'></div>");
	$("#lightbox").append("<span id='closeOverlay' onclick='closeOverlay();'><img src='images/cross.png' style='padding: 8px;height: 40px;border-radius:48px;position:absolute;background-color: white;cursor:pointer;box-shadow:0 0 4px rgba(0,0,0,0.8);z-index:20;'></span>");

}
function closeOverlay(){
	$("#lightbox").fadeOut(600,function(){
		$("#lightbox").remove();
	});
}

function positionTool(selector){
	var left = ($(window).width() - $("#"+selector).width())/2;
	var top = ($(window).height() - $("#"+selector).height())/2;
	$("#"+selector).css({
		"left" : left+"px",
		"top" : top + "px"
	});
}
function positionCloseOverlay(selector){
	var left = $("#"+selector).position().left+$("#"+selector).width()+5;
	var top = $("#"+selector).position().top-20;
	$("#closeOverlay").css({
		"position" : "absolute",
		"top" : top+"px",
		"left" : left+"px"
	});
}

function productClick(name,desc,img,pid){
	createOverlay();
	$("#lightbox").append("<div id='productViewTool' style='padding: 10px;height: 600px;width: 700px;background-color:white;position:absolute;border-radius:10px;overflow-y:auto;'></div>");
	positionTool("productViewTool");
	positionCloseOverlay("productViewTool");
	var cost,rate,sold,ordered;
	$.ajax({
		type:"GET",
		url: "scripts/userServ.php",
		data: {'getProdInfo': pid},
		datatype: 'json',
		async: false,
		success: function(response){
			if(response.indexOf("ERROR") == -1){

				var obj = JSON.parse(response);
				cost = obj.cost;
				rate = obj.rate;
				sold = obj.sold;
				ordered = obj.ordered;
				desc = obj.desc;
				//pid = obj.pid;
				//alert("success : "+cost+ " : " + response);
			}
		}
	});

	if(rate == 0)
		rate = 'none';

	var fill = "<div id='prodImg' style='display:inline-block;'><img src='"+img+"' style='height:200px;'></div><div id='prodInfo'><b><span id='prodInfoName'>"+name+"</span></b><br><br>Rs. <span id='prodInfoCost'>"+cost+"</span><br><br>Rating : <span id='prodInfoRate'>"+rate+"</span><br><br><span id='prodInfoAction'></span></div><br><div id='prodDesc'><span id='editProdDesc'>Edit Description</span><p></p></div>";
	$("#productViewTool").append(fill);
	var actionFill = "";
	if(load != "sell")
		$("#editProdDesc").hide();
	if(ordered == 1 && load == 'sell')
		actionFill = "<b>Item has been Ordered</b><br><br><span id='setSold'>Already Sold</span> | <span id='cancelOrder'>Cancel Order</span>";
	if(sold == 1 && load == 'sell')
		actionFill = "<b>Item has been Sold</b>";
	if(load == "buy" && ordered == 0)
		actionFill = "<button id='placeOrder' onclick='placeOrder("+pid+","+cost+")'>Place Order</button>";

	$("#prodInfoAction").html(actionFill);
	var str = desc.replace(/\n/g, '<br />');
	$("#prodDesc > p").html(str);
	$(document).on("click", "#setSold",function(){
		setSold(pid, cost);
	});

	$(document).on("click", "#cancelOrder",function(){
		cancelOrder(pid, cost);
	});

	$(document).on("click", "#editProdDesc", function(){
		fill = "<div id='editDescText'><textarea placeholder = 'Edit product description' style='outline:none;resize:none;width: 500px;height: 300px;border-radius:5px;border: 1px solid grey;'>"+desc+"</textarea><br><br><button id='saveDesc' style='border:none;background-color:black;color:white;padding:5px;cursor:pointer;box-shadow:0 0 3px rgba(0,0,0,0.8);'>Save</button></div>";
		$("#productViewTool").html(fill);
	});
	$(document).on("click", "#saveDesc", function(){
		var data = $("#editDescText > textarea").val();
		saveDesc(data,pid,name,img);
	});
}

function saveDesc(data,pid,name,img){
	$.ajax({
		type: "POST",
		url : "scripts/userServ.php",
		data: {'saveDesc':pid, 'data': data},
		async: false,
		success: function(response){
			if(response.indexOf("success") > -1){
				closeOverlay();
				//setTimeout(function(){productClick(name, data, img,pid);},1000);
				
			}else{
				//alert('Query Error : '+response);
			}
		}
	});
}

function placeOrder(pid,cost){
	//alert(pid +" : "+cost);
	$.ajax({
		type:"POST",
		url: "scripts/userServ.php",
		data: {'placeOrder' : pid, 'cost' : cost,'u': user},
		async: false,
		success: function(response){
			//alert(response);
			if(response.indexOf("success") > -1){
				$("#productViewTool").html("<span style='padding: 7px;color:white;background-color:black;font-size:15pt;border-radius:3px;box-shadow: 0 0 4px rgba(0,0,0,0.8);'>Order placed</span>");
				setTimeout(function(){					
					closeOverlay();
					fillBuy();
					
				},1000);
			}else{
				//alert(response);
			}
		}
	});
}

function setSold(pid, cost){
	$.ajax({
		type: "POST",
		url: "scripts/userServ.php",
		data: {'setSold' : pid, 'cost' : cost},
		async: false,
		success: function(response){
			if(response.indexOf('success') > -1){
				$("#prodInfoAction").html("<b>Item has been sold</b>");
			}
		}
	});
}

function cancelOrder(pid, cost){
	//alert("In cancel Order");
	$.ajax({
		type: "POST",
		url: "scripts/userServ.php",
		data: {'cancelOrder' : pid, 'cost' : cost},
		async: false,
		success: function(response){
			//alert(response);
			if(response.indexOf('success') > -1){
				$("#prodInfoAction").html("");
			}
		}
	});
}

function pOrders(){
	$.ajax({
		type: "GET",
		url: "scripts/userServ.php",
		data: {'pOrders' : user},
		async:false,
		success: function(response){
			if(response.indexOf("ERROR") == -1){
				$("#rightContent").html(response);
			}else{
				//alert(response);
			}
		}
	});
}

function pOrdersBuy(){
	$.ajax({
		type: "GET",
		url: "scripts/userServ.php",
		data: {'pOrdersBuy' : user},
		async:false,
		success: function(response){
			if(response.indexOf("ERROR") == -1){
				$("#rightContent").html(response);
			}else{
				//alert(response);
			}
		}
	});
}

function deleteProd(pname){
	//alert("In deleteProd : "+pname);
	$.ajax({
		type: "POST",
		url : "scripts/userServ.php",
		data: {'deleteProd' : pname,'u' : user},
		async: false,
		success: function(response){
			if(response.indexOf("success") > -1){
				fillSell();
			}else{
				//alert(response);
			}
		}
	});
}

function changePicture(pid){
	createAddProductTool();
	pictureUpload(pid);	
}

function cancelOrderBuy(oid){
	$.ajax({
		type: "POST",
		url: "scripts/userServ.php",
		data: {'cancelOrderBuy' : oid},
		async: false,
		success: function(response){
			if(response.indexOf("success") > -1){
				pOrdersBuy();
			}else{
				alert("Error");
			}
		}
	});
}