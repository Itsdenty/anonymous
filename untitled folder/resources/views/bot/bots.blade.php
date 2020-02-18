<? php 
  @extends('layouts.user')
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href='{{ url("css/jsplumbtoolkit-defaults.css") }}' />
<link rel="stylesheet" href='{{ url("css/main2.css") }}' />
<link rel="stylesheet" href='{{ url("css/jsplumbtoolkit-demo.css") }}'>
<link rel="stylesheet" href='{{ url("css/demo.css") }}'>
<title>SendMunk | SMS Bot</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    var maxField = 3; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input style="margin-left: 130px;margin-bottom: 15px; border: 1px solid red; height: 40px;" type="text" name="field_name[]" value=""/><a href="javascript:void(0);" title="Remove Field" class="remove_button"><span class="glyphicon glyphicon-minus"></span></a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
</script>

<style>
.box {
  color: #000;
  padding: 20px;
  display: none;
  margin-top: 20px;
}
</style>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("select").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".box").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".box").hide();
            }
        });
    }).change();
});
</script>
</head>
<body>

  <button class="btn btn-lg btn-danger" style="outline:none; color: white; position:absolute;top:3%;left: 85%;" data-toggle="modal" data-target="#exampleModal">
            Add Question
    </button>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div>
                        <select class="custom" onchange="">
                            <option>Question Type</option>
                            <option value="red">Open-ended Question</option>
                            <option value="green">Close-ended Question</option>
                        </select>
                    </div>
                    <div class="red box">
                        <form>
                        <textarea id="textArea1" class="form-control textspace" placeholder="Type your question here..." rows="4" required></textarea>
                        </form>
                    <div>
                    <input type="file" accept="image/*" style="margin-bottom: 30px">
                    <div class="dropdown">
                    <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Match Contact Field
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Name</a>
                    <a class="dropdown-item" href="#">Email address</a>
                    <a class="dropdown-item" href="#">Custom Fields</a>
                    </div>
                    </div>
                    </div>
              </div>
              <div class="green box">
              <div style="display:flex;">
                <div class="dropdown">
                <textarea id="textArea2" class="form-control textspace" placeholder="Type your question here..." rows="4" required></textarea>
                <div><input type="file" accept="image/*" style="margin-top: 15px; margin-bottom: 30px;"></div>
                <button class="btn btn-sm btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Match Contact Field
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Name</a>
                <a class="dropdown-item" href="#">Email address</a>
                <a class="dropdown-item" href="#">Custom Fields</a>
                </div>
              </div>
              <div class="field_wrapper">
                  <div><label style="margin-left: 15px;">Enter Options:</label>
                      <input style="margin-left: 17px;margin-bottom: 15px; border: 1px solid red; height: 40px;" type="text" name="field_name[]" value=""/>
                      <a href="javascript:void(0);" title="Add Field" class="add_button" title="Add field"><span class="glyphicon glyphicon-plus" ></span></a>
                  </div>
              </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="getDivOnClick();">Save</button>
                </div>
            </div>
        </div>
    </div>
  
    <div class="jtk-demo-main">
        <div class="jtk-demo-canvas canvas-wide drag-drop-demo jtk-surface jtk-surface-nopan" id="canvas">
        <div class="divArray">
            <div class="window" id="dragDropWindow1"><h4 style="text-align:center;">Trigger</h4></div>
            <div class="window card2" id="dragDropWindow2"><h4 style="text-align:center;">2</h4></div>
            <div class="window" id="dragDropWindow3"><h4 style="text-align:center; line-height:100px">3</h4></div>
            <div class="window" id="dragDropWindow4"><h4 style="text-align:center; line-height:100px">4</h4></div>
        </div>
            <div id="list"></div>
        </div>
    </div>

<!-- JS -->
<script src='{{ "dist/js/jsplumb.js" }}'></script>
<!-- /JS -->

<!--  demo code -->
<script src='{{ url("js/demo2.js") }}'></script>
<script src='{{ url("js/demo-list.js") }}'></script>
<script src='{{ url("js/bots.js") }}'></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
</body>
</html>
