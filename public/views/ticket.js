setTimeout(function() {
    $("#tenant-drop-contract")
        .selectpicker({
            liveSearch: true
        })
        .ajaxSelectPicker({
            ajax: {
                url: "/tenant/query",
                data: function() {
                    var params = {
                        q: "{{{q}}}"
                    };
                    return params;
                }
            },
            locale: {
                emptyTitle: "Type & Select a Tenant.."
            },
            preprocessData: function(data) {
                var i,
                    l = data.length,
                    array = [];
                if (l) {
                    for (i = 0; i < l; i++) {
                        array.push(
                            $.extend(true, data[i], {
                                text: data[i].name,
                                value: data[i].id,
                                data: {
                                    icon: "icon-person",
                                    subtext: data[i].email
                                }
                            })
                        );
                    }
                }
                return array;
            },
            preserveSelected: false,
            bindEvent : "keypress"
        });
}, 600);

$("#tenant-drop-contract").on("changed.bs.select",   function(e, clickedIndex, newValue, oldValue) {
    
    $.ajax({
        type: "POST",
        url: '/tenant/fetch',
        data: { _ref : this.value },
        success: function(response) {
            if(response.status == 'success' ){
                $('#contract_list_div').html(response.active_contracts);
                $('#contract_list_div').find('select').selectpicker({
                    liveSearch: true,
                    dropupAuto: false,
                    size: 5
                });
            }
        }
    });
});

