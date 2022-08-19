$("#lanuage").autocomplete({
    // Function triggers when typing in text box
    source: function(data, cb){
        $.ajax({
            url: '<?= DIR ?>complaints/getcustomer',
            method: 'GET',
            dataType: 'json',
            data: {
                name:data.name
            },
            success: function(res){
                var d = $.map(res, function(name){
                    return {
                        label: name,
                        value: name
                    }
                });
                cb(d);
            }
        });
    }
});