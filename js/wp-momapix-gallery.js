function insert_picture($momaurl, $moma2, $date, $location, $title, $credit, $caption, $momaurlitem)
    {
	string = '<a target="_blank" href="' + $momaurl + '"><img src="' + $momaurl + '" alt="' + $moma2 + '" /></a></br>' +
	'<p> ' + $date + ' </p><p>' + $location + '' + $title + '</p><p>' + $credit + '</p><p><a href="'+ $momaurlitem +'">' + $momaurlitem + '</a></p><p>' + $caption + '</p>' ;
		
	var win = window.dialogArguments || opener || parent || top;
				
	
    win.send_to_editor(string);
      	      				
      	return true;
								
    }
                        

    


