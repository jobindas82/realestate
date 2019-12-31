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
                },
                success : function(response){
                    $('#contract_emirates_id').val('');
                    $('#contract_email').val('');
                    $('#contract_passport').val('');
                    $('#contract_phone').val('');
                    $('#contract_mobile').val('');
    
                    $('.tenant-set').removeClass('focused');
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
                $('#contract_emirates_id').val(response.emirates_id);
                $('#contract_email').val(response.email);
                $('#contract_passport').val(response.passport_no);
                $('#contract_phone').val(response.phone);
                $('#contract_mobile').val(response.mobile);

                $('.tenant-set').addClass('focused');
            }
        }
    });
});

