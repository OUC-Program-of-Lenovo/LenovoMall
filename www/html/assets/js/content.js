//弹框
$("#dialog_close").click(function(){
	$(".dialog").css({"display":"none"});
})
$(".goods").click(function(){
	$(".dialog").css({"display":"block"});
	funShowDivCenter(".dialog_content");
})

function funShowDivCenter(div) {
    var top = ($(window).height(0
    	) - $(div).height()) / 2;
    var left = ($(window).width() - $(div).width()) / 2;
    var scrollTop = $(document).scrollTop();
    var scrollLeft = $(document).scrollLeft();
    $(div).css({ display:'block',position: 'absolute', 'top': top + scrollTop, left: left + scrollLeft });
}

//数量加减
$("#sub_num").click(function(){
	var num = parseInt($("#num").val())-1;
	if(num<0){
		$("#num").val(0);
	}
	else{
		$("#num").val(num);
	}
})
$("#add_num").click(function(){
	var num = parseInt($("#num").val())+1;
	$("#num").val(num);
})

//添加到购物车
$("#add_cart").click(function(){
	var name = $(".dialog_text").find("h1").text();
	var price = $(".dialog_text").find("p").text();
	var num = parseInt($("#num").val());
	var table = $(".cart-tbody");
	var table_data = $("<tr>").appendTo(table);
	var table_name = $("<td>").text(name).appendTo(table_data);
	var table_price = $("<td>").text(price).appendTo(table_data);
	var table_num = $("<td>").text(num).appendTo(table_data);
	$(".dialog").css({"display":"none"});
})


//列表交互
$(document).ready(function(){
	var ul_list = $(".rules");
	var i;
	function appendList(){
		var url = "";
		$.ajax({
			type:"GET",
			url:url,
			dataType:"json",
			success:function(msg){
				if(msg.status == 1){
					jsonObj=JSON.parse(msg);
					console.log(jsonObj);
					for(i=0;i<jsonObj.length;i++){
						var li = $("<li>").addClass("goods").appendTo(ul_list);
						var img = $("<img>").attr("src",jsonObj.img).appendTo(li);
						var name = $("<h1>").text(jsonObj.name).appendTo(li);
						var price = $("<p>").text(jsonObj.price).appendTo(li);
					}
				}
			}
		})
	}
})
