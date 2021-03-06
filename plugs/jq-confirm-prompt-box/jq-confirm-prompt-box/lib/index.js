try{
  const $ = jQuery = require('jquery');
}catch(e){
  if(e){}
}

(function($){

  function rmpm(i,e){
    let pm = $(i);
    pm.fadeOut(e.speed, function(){
      pm.remove()
    });
  }

  $.xPrompt = function(config, cb) {

    let obj = $.extend({}, $.xPrompt.defaults, config),
    div = $('<div />'),
    btn = $('<button />'),
    defaults = {
      header: 'by sending this you are signing an agreement between you and the other send on this price and service',
      type: 'text',
      speed: 'fast',
      placeholder: 'cannot be empty',
      cBtn: 'Cancel',
      sBtn: 'Send',
      error: 'cannot be empty'
    };

    if(!cb && typeof obj === 'function'){
      cb = obj;
      obj = {};
    }

    for (const i in defaults) {
      if(!obj[i]){
        obj[i] = defaults[i];
      }
    }

    try{
      $('body').append(
        div.clone().addClass('pt-Mask').append(
          div.clone().addClass('pt').append(
            div.clone().addClass('pt-Center pt-Head').text(obj.header),
            div.clone().addClass('pt-Body').append(
              $('<input />', {
                type: obj.type,
                class: 'pt-Input',
                placeholder: obj.placeholder
              }),
              $('<small />', {
                class: 'pt-Error'
              })
            ),
            div.clone().addClass('pt-Footer').append(
              btn.clone().attr({
                type:'button',
                id: 'pt-Cancel',
                class:'pt-Btn'
              }).text(obj.cBtn),
              btn.clone().attr({
                type:'button',
                id: 'pt-Submit',
                class:'pt-Btn pt-Right'
              }).text(obj.sBtn)
            )
          )
        )
      );

      $('.pt-Mask').fadeIn(obj.speed),
      $('#pt-Cancel').off().on('click', function(){
        rmpm('.pt-Mask', obj)
      }),
      $('#pt-Submit').off().on('click', function(){
        let data = $('.pt-Input').val();
        if(!data || data === ''){
          $('.pt-Error').text(obj.error);
          return;
        }
        cb(data);
        rmpm('.pt-Mask', obj)
      });

    } catch(err) {
      if(err) {return console.log(err)}
    }
  }

  $.xPromptQ = function(config, cb) {

    let obj = $.extend({}, $.xPromptQ.defaults, config),
    div = $('<div />'),
    btn = $('<button />'),
    defaults = {
      header: 'prompt',
      speed: 'fast',
      cBtn: 'Cancel',
      sBtn: 'Submit'
    };

    if(!cb && typeof obj === 'function'){
      cb = obj;
      obj = {};
    }

    for (const i in defaults) {
      if(!obj[i]){
        obj[i] = defaults[i];
      }
    }

    try{
      $('body').append(
        div.clone().addClass('ptQ-Mask').append(
          div.clone().addClass('ptQ').append(
            div.clone().addClass('pt-Center pt-Head').text(obj.header),
            div.clone().addClass('ptQ-Footer').append(
              btn.clone().attr({
                type:'button',
                id: 'ptQ-Cancel',
                class:'pt-Btn'
              }).text(obj.cBtn),
              btn.clone().attr({
                type:'button',
                id: 'ptQ-Submit',
                class:'pt-Btn pt-Right'
              }).text(obj.sBtn)
            )
          )
        )
      );

      $('.ptQ-Mask').fadeIn(obj.speed),
      $('#ptQ-Cancel').off().on('click', function(){
        cb(false);
        rmpm('.ptQ-Mask', obj)
      }),
      $('#ptQ-Submit').off().on('click', function(){
        cb(true);
        rmpm('.ptQ-Mask', obj)
      });

    } catch(err) {
      if(err) {return console.log(err)}
    }
  }
})(jQuery);
