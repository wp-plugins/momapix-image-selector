function insert_picture($momaurl, $moma2, $date, $location, $title, $credit, $caption, $momaurlitem)
    {
	string = '<div style="background: none repeat scroll 0 0 white; border: 1px outset gray; color: gray; font-size: 12px; font-style: italic; line-height: 1em; padding: 5px; text-align: justify; width: 100%;"><a target="_blank" href="' + $momaurl + '"><img src="' + $momaurl + '" alt="' + $moma2 + '" /></a>' +
	'<br><p> ' + $date + ' </p><p>' + $location + '' + $title + '</p><p>' + $credit + '</p><p><a href="'+ $momaurlitem +'">' + $momaurlitem + '</a></p><p>' + $caption + '</p> </div>';
		
	var win = window.dialogArguments || opener || parent || top;
				
	
    win.send_to_editor(string);
      	      				
      	return true;
								
    }
                        

    


