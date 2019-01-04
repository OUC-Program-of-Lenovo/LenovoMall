



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
    appendList();
    function appendList(){
        console.log(222);
        var url = "/assets/js/data.json";
        $.ajax({
            type:"GET",
            url:url,
            dataType:"json",
            success:function(jsonObj){
                console.log(111);
                //var jsonObj=JSON.parse(msg);
                console.log(jsonObj);
                for(i=0;i<jsonObj.length;i++){
                    var id = jsonObj[i].item_id;
                    var li = $("<li>").addClass("goods").attr("id",id).appendTo(ul_list);
                    var img = $("<img>").attr("src",jsonObj[i].img).appendTo(li);
                    var name = $("<h1>").text(jsonObj[i].name).appendTo(li);
                    var price = $("<p>").text(jsonObj[i].price).appendTo(li);
                }
                //点击获取详情
                $(".goods").on("click",function(){
                    var id = $(this).attr("id");
                    var dialog_content = $(".dialog_content");
                    var dialog_text = $(".dialog_text");
                    console.log(id);
                    for(i=0;i<jsonObj.length;i++){
                        if(id == jsonObj[i].item_id){
                            dialog_content.find("img").attr("src",jsonObj[i].img);
                            dialog_text.find("h1").text(jsonObj[i].name);
                            dialog_text.find("p").text(jsonObj[i].price);
                            $(".description").text(jsonObj[i].description);
                        }
                    }
                    $(".dialog").css({"display":"block"});
                    funShowDivCenter(".dialog_content");
                })

            }
        })
    }
    //点击获取详情
    // $(".goods").on("click",function(){
    // 	var url = "/assets/js/data.json";
    // 	var id = $(this).attr("id");
    // 	var dialog_content = $(".dialog_content");
    // 	var dialog_text = $(".dialog_text");
    // 	console.log(id);
    // 	$.ajax({
    // 		type:"GET",
    // 		url:url,
    // 		// data:{item_id:id},
    // 		dataType:"json",
    // 		success:function(jsonObj){
    // 			console.log(111);
    // 			//var jsonObj=JSON.parse(msg);
    // 			console.log(jsonObj);
    // 			dialog_content.find("img").attr("src",jsonObj.img);
    // 			dialog_text.find("h1").text(jsonObj.name);
    // 			dialog_text.find("p").text(jsonObj.price);
    // 			$(".description").text(jsonObj.description);

    // 		}
    // 	})
    // 	$(".dialog").css({"display":"block"});
    // 	funShowDivCenter(".dialog_content");

    // })
    function funShowDivCenter(div) {
        var top = ($(window).height(0
        ) - $(div).height()) / 2;
        var left = ($(window).width() - $(div).width()) / 2;
        var scrollTop = $(document).scrollTop();
        var scrollLeft = $(document).scrollLeft();
        $(div).css({ display:'block',position: 'absolute', 'top': top + scrollTop, left: left + scrollLeft });
    }
})


//关闭弹框
$("#dialog_close").click(function(){
    $(".dialog").css({"display":"none"});
})



//购物车更新
