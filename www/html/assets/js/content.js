/*居中显示函数*/
function funShowDivCenter(div) {
        var top = ($(window).height() - $(div).height()) / 2;
        var left = ($(window).width() - $(div).width()) / 2;
        var scrollTop = $(document).scrollTop();
        var scrollLeft = $(document).scrollLeft();
        $(div).css({ display:'block',position: 'absolute', 'top': top + scrollTop, left: left + scrollLeft });
    }


/*列表页动态显示*/
function show_goods(){
	var goods_container = $('.content-container');//content最外层div
	goods_container.html('');//清空div

	var item_url = '/items';//商品接口

	$.ajax({
		type:"GET",
		url:item_url,
		dataType:"json",
		beforeSend: function() {
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success:function(msg){
        	if(msg.status==1){
        		console.log(msg);
        		var goods = msg.items;
        		console.log(goods);
                var html = '';
                html += '<h1 class="cover-heading"><span class="hint--right" aria-label="Notice!">All</span></h1>';
                html += '<div class="lead">';
                html += '<ul class="rules">';
                html += '</ul>';
                html += '</div>'
                goods_container.html(html);
                flush_data();
                var ul_list = $(".rules");//列表ul
                // var jsonObj=JSON.parse(goods);//商品列表json数据
                var jsonObj = goods;
                //加载列表
                for(var i=0;i<jsonObj.length;i++){
                    var id = jsonObj[i].item_id;
                    var li = $("<li>").addClass("goods").attr("id",id).appendTo(ul_list);
                    var img = $("<img>").attr("src","/upload/images/picture/"+jsonObj[i].avatar).appendTo(li);
                    var name = $("<h1>").text(jsonObj[i].name).appendTo(li);
                    var price = $("<p>").text('￥' + jsonObj[i].price).appendTo(li);
                }
                //获取商品详情
                $(".goods").on("click",function(){
                	var id = $(this).attr("id");
                	var dialog = $(".dialog");
                    var dialog_content = $(".dialog_content");
                    var dialog_text = $(".dialog_text");
                    console.log(id);
                    for(i=0;i<jsonObj.length;i++){
                        if(id == jsonObj[i].item_id){
                        	dialog.attr("title",id);
                            dialog_content.find("img").attr("src",jsonObj[i].img);
                            dialog_text.find("h1").text(jsonObj[i].name);
                            dialog_text.find("p").text(jsonObj[i].price);
                            $(".description").text(jsonObj[i].description);
                        }
                    }
                	// var id = $(this).attr("id");//获取id
                	// for(var i=0;i<jsonObj.length;i++){
                	// 	if(id == jsonObj[i].item_id){
	                // 		//创建详情弹窗
		               //  	var dialog = $("<div>").addClass("dialog").appendTo(goods_container);
		               //  	var dialog_content = $("<div>").addClass("dialog_content").appendTo(dialog);
		               //  	var dialog_close = $("<button>").attr("id","dialog_close").text("×").appendTo(dialog_content);
		               //  	var dialog_img = $("<img>").attr("/upload/images/picture/"+jsonObj[i].avatar).appendTo(dialog_content);
		               //  	var dialog_text = $("<div>").addClass("dialog_text").appendTo(dialog_content);
		               //  	var text_h1 = $("<h1>").text(jsonObj[i].name).appendTo(dialog_text);
		               //  	var text_p = $("<p>").text('￥' + jsonObj[i].price).appendTo(dialog_text);
		               //  	var sub = $("<button>").addClass("num").attr("id","sub_num").text("-").appendTo(dialog_text);
		               //  	var input = $("<input>").attr("type","text","id","num","value","1").appendTo(dialog_text);
		               //  	var add = $("<button>").addClass("num").attr("id","add_num").text("＋").appendTo(dialog_text);
		               //  	var add_cart = $("<button>").attr("id","add_cart").text("ADD").appendTo(dialog_text);
		               //  	var description = $("<p>").addClass("description").text(jsonObj[i].description).appendTo(dialog_content);
	                // 	}
                	// }

                	//详情弹窗显示
                	$(".dialog").css({"display":"block"});
                	funShowDivCenter(".dialog_content");
                })
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

				//关闭弹框
				$("#dialog_close").click(function(){
				    $(".dialog").css({"display":"none"});
				})
        	} else {
                show_pnotify("Failed!", msg.message, "error");
            }
        }
	})
}


$(document).ready(function(){
	show_goods();
	$(".goods-all").click(function(){
		show_goods();
	})
	
})




/*购物车部分*/
 function GetGoods(){
    $.ajax({
    type:"GET",
    url:"/items/get_itemsInCart",
    dataType:"json",
    success:function(msg){//msg
        console.log('msg:'+msg);
        //if(msg.status==1)//{
       // var cartInfo=json.parse(msg.value);
       $(".cart-tbody").html('');
       GoodsInfo = msg.items;
       var goods = GoodsInfo
       console.log(GoodsInfo);
       var keys = Object.keys(GoodsInfo);
       for(i=0;i<keys.length;i++)
        {  // console.log(i);
        	            console.log("From first-source:"+GoodsInfo[keys[i]]);
            var name=GoodsInfo[keys[i]].name;

            var price=GoodsInfo[keys[i]].price;
            var num=GoodsInfo[keys[i]].num;

            var table=$(".cart-tbody");
            var table_data=$("<tr>").appendTo(table);
            var table_name=$("<td>").text(name).appendTo(table_data);
            var table_price=$("<td>").text(price).appendTo(table_data);
            var table_num=$("<td>").text(num).appendTo(table_data);
        }
        //}//if(msg.status==1)
    }// success:function
})//.ajax
}


//购物车更新(用户登录后，自动显示其购物车中的信息)
GetGoods();
$(document).ready(function()
{
 GetGoods();

})


//添加到购物车(动态修改版)
$(document).ready(function(){

  	$("#add_cart").click(function(){

    	var current_id = $(".dialog").attr("title");//试图添加进购物车的pc的信息
    	var current_num = parseInt($("#num").val());
      	console.log("current_num:"+ current_num);
   		console.log("current_id:"+ current_id);
    	getCartGoods(current_num);
    	function getCartGoods(current_num){//向后台购物车请求数据.
    		for(var j=0;j<current_num;j++){
    			$.ajax({
    				type:"POST",
    				url:"/user/cart/add/"+ current_id,
    				// data:{"current_id":"current_num"},
    				dataType:"json",
    				success:function(CartGoods){
       					// var cartInfo=json.parse(msg);
       					console.log("cart:"+CartGoods);
       					GetGoods();
				        //  $(".cart-tbody").html('');
				        // for(i=0;i<CartGoods.length;i++)
				        // {
				        //      var name=CartGoods[i].Name;
				        //      var price=CartGoods[i].Price;
				        //      var num=CartGoods[i].Num;
				        //      console.log("From cart-add func:"+CartGoods[i].Name);
				        //      var table=$(".cart-tbody");
				        //      var table_data=$("<tr>").appendTo(table);
				        //      var table_name=$("<td>").text(name).appendTo(table_data);
				        //      var table_price=$("<td>").text(price).appendTo(table_data);
				        //      var table_num=$("<td>").text(num).appendTo(table_data);
				        // }
    				}
  				})//.ajax
    		}
    	}//function GetCartGoodsIdNum()
		$(".dialog").css({"display":"none"});
	})
})

//提交购物车
$(document).ready(function(){
	$("#cart_submit").click(function(){
		$.ajax({
			type:"POST",
    		url:"/user/order/submit",
    		data:"Goods from cart to order!",
    		success:function(){
    			console.log("order get.");
    			GetGoods();
    		}
		})
	})
})


//列表交互
// $(document).ready(function(){
//     var ul_list = $(".rules");
//     var i;
//     appendList();
//     function appendList(){
//         console.log(222);
//         var url = "/assets/js/data.json";
//         $.ajax({
//             type:"GET",
//             url:url,
//             dataType:"json",
//             success:function(jsonObj){
//                 console.log(111);
//                 //var jsonObj=JSON.parse(msg);
//                 console.log(jsonObj);
//                 for(i=0;i<jsonObj.length;i++){
//                     var id = jsonObj[i].item_id;
//                     var li = $("<li>").addClass("goods").attr("id",id).appendTo(ul_list);
//                     var img = $("<img>").attr("src",jsonObj[i].img).appendTo(li);
//                     var name = $("<h1>").text(jsonObj[i].name).appendTo(li);
//                     var price = $("<p>").text(jsonObj[i].price).appendTo(li);
//                 }
//                 //点击获取详情
//                 $(".goods").on("click",function(){
//                     var id = $(this).attr("id");
//                     var dialog_content = $(".dialog_content");
//                     var dialog_text = $(".dialog_text");
//                     console.log(id);
//                     for(i=0;i<jsonObj.length;i++){
//                         if(id == jsonObj[i].item_id){
//                             dialog_content.find("img").attr("src",jsonObj[i].img);
//                             dialog_text.find("h1").text(jsonObj[i].name);
//                             dialog_text.find("p").text(jsonObj[i].price);
//                             $(".description").text(jsonObj[i].description);
//                         }
//                     }
//                     $(".dialog").css({"display":"block"});
//                     funShowDivCenter(".dialog_content");
//                 })

//             }
//         })
//     }
    
// })




