$(document).ready(function(){
  $('.nav-icon').click(function(event){
    $('.selected').toggleClass('selected');
    $(this).toggleClass('selected');

    var process = $(this).data('process');
    process = '#' + process + '-group';

    $('.open').toggleClass('open');
    $(process).toggleClass('open');
  });

  $('input[type="checkbox"]').click(function(){
    event.stopPropagation();
    $(this).toggleClass('checked');
  });

  $('.nav-item').click(function(event){
    $('.view-container').empty();

    var name = $(this).attr('id');
    var inputName = 'input[name="' + name + '"]';

    var saveToFile = false;
    var processURL = name + '.php'

    if($(inputName).hasClass('checked')){
      saveToFile = true;
    }

    if(name.indexOf("builder") >= 0){
      processURL = "../builders/" + processURL;
    } else if(name.indexOf("updater") >= 0){
      processURL = "../updaters/" + processURL;
    } else if(name.indexOf("inserter") >= 0){
      processURL = "../inserters/" + processURL;
    } else if(name.indexOf("viewer") >= 0){
      processURL = "../viewers/" + processURL;
    }

    //var dataToSend = saveToFile.serialize();

    $.ajax({
      method : "POST",
      url : processURL,
      data : {saveStatus: 'true'},
      success: function(response){
        $('.view-container').append(response);
      }
    });

  });
});
