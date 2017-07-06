console.log('Connected');

$('#submit').click(function(){
    var duplicate=false;
    
    $('input[id^=field]').each(function(){
        var $this = $(this);
        if ($this.val()===''){ return;}
        $('input[id^=field]').not($this).each(function(){
            if ( $(this).val()==$this.val()) {duplicate=true;}
        });
    });
    
    if(duplicate) {
    	alert('All names must be unique. Please double check the names to make sure they are unique.');
    } else {
    	document.form.submit();
    }


});