function load_personal_orders() {
    var container = $(".content-container");
    container.html('');
    html = '<div class="admin-challenge"><nav class="navbar navbar-default" role="navigation"><div class="container-fluid">';
    html += '<div class="navbar-header"><a class="navbar-brand" href="#">Goods</a></div>';
    html += '<div><button type="button" class="admin-challenge-create btn btn-default navbar-btn">Create</button></div></div></nav></div>';
    var url = '/orders/get';
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        beforeSend: function() {
            NProgress.start();
        },
        complete: function() {
            NProgress.done();
        },
        success: function(msg) {
            var available = {
                'order_id': 'ID',
                'item_id': 'Item',
                'amount': 'Amount',
                'rcv_name': 'Receiver',
                'rcv_address': 'Address',
                'rcv_phone': 'Phone',
                'status': 'Status',
                'time': 'Add Time'
            };
            var available_keys = Object.keys(available);
            var order_info = msg.items;
            html += '<div class="table-responsive admin-items"><table class="table table-hover">';
            html += '<thead><tr>';
            for (var i = 0; i < available_keys.length; i++) {
                html += '<th><span class="admin-items-key">' + available[available_keys[i]] + '</th>';
            }
            html += '<th><span class="admin-items-key">' + 'Confirm' + '</th>';
            html += '<th><span class="admin-items-key">' + 'Delete' + '</th>';
            html += '</span></tr></thead><tbody>';
            if (order_info != null) {
                for (var i = 0; i < order_info.length; i++) {
                    order_info[i].time = TimeStamp2Date(order_info[i].add_time);
                    order_info[i].item_id = get_item_number_by_item_id(order_info[i].item_id);
                    if (order_info[i].status == 0) {
                        order_info[i].status = '待发货'
                    } else {
                        order_info[i].type = '已发货'
                    }
                    html += '<tr>';
                    html += '<td>';
                    html += '<span class="admin-items-value">';
                    html += order_info[i][available_keys[j]];
                    html += '</span>';
                    html += '</td>';
                    html += '</tr>';
                }
            }
            html += '</tbody></table></div>';
            container.html(html);
            flush_data();
        }
    });
}