function insert_picture(momaurl)
    {
	string = '<a target="_blank" href="' + momaurl + '"><img src="' + momaurl + '" /></a>';
		
	var win = window.dialogArguments || opener || parent || top;
				
	win.send_to_editor(string);
      	      				
      	return true;
								
    }
                        

    


