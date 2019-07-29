$(function(){
    
    $.get('dashboard/xhrGetListings', function(o){
        //console.log(o);
        for(var i=0; i<o.length; i++){
            $('#listInserts').append('<div>' + o[i].text + ' <a class = "del" rel="' + o[i].id + '"href="#">X</a></div>');
        }
    }, 'json');
    
    $('#randomInsert').submit(function(){
        var url = $(this).attr('action');
        var data = $(this).serialize();
        console.log(url);
        
        //ajax post!
        $.post(url, data, function(o){
            $('#listInserts').append('<div>' + o.text + ' <a class = "del" rel="' + o.id + '" href="#">X</a></div>');
        }, 'json');
        
        $('#inputText').val('');
        return false; 
    });
    
    //make this live instead.
    //Attach an event handler for all elements which match the current selector, now and in the future
    $('#listInserts').on('click', '.del', function(){
        //$('.del').click(function(){
            delItem = $(this);
            var rel = $(this).attr('rel'); 
            $.post('dashboard/xhrDeleteListing', {'id':rel}, function(o){
                delItem.parent().remove(); 
            }, 'json');
        });
    
});