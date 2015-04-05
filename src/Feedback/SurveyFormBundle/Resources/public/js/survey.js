(function(){
  $('.survey-template').on('focus', '.form-control', clearError);
  $('.survey-template').on('click', '.radio-inline', clearRadio);

  $( document ).ready(function (){
      
      var createTextArea = function(i, r) {
          var textAreaHtml = '<textarea style="resize: none; " class="form-control '+ r +' " rows="3" name="' + i +'"></textarea>';
          
          return textAreaHtml;
      }
      var createTextField = function(i, r){
          var textFieldHtml = '<div class="form-group">';
              textFieldHtml += '<input class="form-control  '+ r +'" rows="3" name="' + i +'" />';
              textFieldHtml += '</div>';
          return textFieldHtml;
      }
      var createCombobox = function(i, o, r){
          var combobox = '<div class="form-group">';
              combobox += '<select class="form-control  '+ r +'" name="' + i +'">';
              combobox += '<option value=""></option>'
              $(o).each(function(key, val){
                  combobox += '<option>'+ val +'</option>'
              });
              combobox += '</select></div>';
          return combobox;
      }
      var validateCurrentForm = function(p){
        var required_fields = $(p).find('.required-field'),
          req_c = 0;
        $(required_fields).each(function(key, val){ 
          if($(val).is('input[type=radio]')){
            var container = $(val).parents('.form-group'),
                r_name = $(val).attr('name'),
                check = $('input[name='+ r_name +']:checked').length;
                if(check == 0){
                  req_c++;
                  $(container).find('.radio-inline').css({'background': '#f2dede', 'border-color' : '#a94442' });
                }
          }
          else{
            var check = $(val).val();
            if(check == ""){
              req_c++;
              $(val).css('border', '1px solid #A94442');
            }
          }
        });
        console.log(req_c);
        if(req_c > 0)
          return false;
        else
          return true;
      }

      var handleResult = function (data) {
        var el = $('#'+ data.id);
            $('.form-group').removeClass('has-error');
        if(data.field){
            var container = $('#'+ data.field).parent().parent();
            container.addClass('has-error');
        }
        

        var addClass = (data.success) ? 'alert-success' : 'alert-danger';
        el.removeClass('alert-success').removeClass('alert-danger');
        el.addClass(addClass);
        el.find('p').text(data.msg);
        el.slideDown(400, function() {
            setTimeout(function() {
            el.slideUp();
            }, 5000);
        });
    }
      Object.size = function(obj) {
          var size = 0, key;
          for (key in obj) {
              if (obj.hasOwnProperty(key)) size++;
          }
          
          return size;
      };
      
      var displayQuiz = function(res) {

          var questions = "";
          var iterator = 1;
          var layout = null;
          
          if(Object.size(res) === 1)
          {
              $("#next").prop('disabled', true);
              $("#previous").prop('disabled', true);
              $("#submit").prop('disabled', false);
          }
          
          if(res.length === 0)
          {
              alertify.alert('No Questions Created');
              $("#previous").prop('disabled', true);
              $("#submit").prop('disabled', true);
              $("#next").prop('disabled', true);
          }   
          
          for(var prop in res)
          {   
              if(res.hasOwnProperty(prop))
              {
                  layout = $('<div/>',{
                          id: "category" + iterator,
                          html: '<h4>' + prop + '</h4>',
                          class: 'layout2'
                  });
                  
                  $("#question_form").append(layout);
                  
                  for(var i=0; i<res[prop].length; i++)
                  {
                     var type = res[prop][i].type,
                        id = res[prop][i].id,
                        options = res[prop][i].options,
                        optional = res[prop][i].optional,
                        description = res[prop][i].description;
                     questions += "<div class='form-group'>";
                      switch(type){
                          case 'rating':
                              questions += '<div class="layout1">';
                              questions += '<label for="' + id +'">' + description + '</label> <br />';
                              questions += '</div>';
                              questions += '<div class= "layout">';
                              
                              questions += '<label class="radio-inline"><span class="input-group-addon">' +
                                          '<input type="radio" class="'+ optional + '" name="' + id +'" value="1" />'
                                          +"1</span></label>";
                                          
                                          
                              questions += '<label class="radio-inline"><span class="input-group-addon">' +
                                          '<input type="radio" class="'+ optional + '" name="' + id +'" value="2" />'
                                          +"2</span></label>";
                                          
                              questions += '<label class="radio-inline"><span class="input-group-addon">' +
                                          '<input type="radio" class="'+ optional + '" name="' + id +'" value="3" />'
                                          +"3</span></label>";
                                          
                                          
                              questions += '<label class="radio-inline"><span class="input-group-addon">' +
                                          '<input type="radio" class="'+ optional + '" name="' + id +'" value="4" />'
                                          +"4</span></label>";
                              
                              questions += "<br />";
                              questions += "</div>"
                              break;
                          case 'textfield':
                              questions += '<div class="layout1">';
                              questions += '<label for="' + id +'">' + description + '</label> <br />';
                              questions += '</div>';
                              questions += createTextField(id, optional) + "</div>";
                              questions += "<br />";
                              break;
                          case 'combobox':
                              questions += '<div class="layout1">';
                              questions += '<label for="' + id +'">' + description + '</label> <br />';
                              questions += '</div>';
                              questions += createCombobox(id, options, optional) + "</div>";
                              questions += "<br />";
                              break;
                          default:
                              questions += '<div class="layout1">';
                              questions += '<label for="' + id +'">' + description + '</label> <br />';
                              questions += '</div>';
                              questions += createTextArea(id,optional) + "</div>";
                              questions += "<br />";
                              break;
                      }
                     // if(res[prop][i].type === 'rating')
                     // {    
                     //      questions += '<div class="layout1">';
                     //      questions += '<label for="' + res[prop][i].id +'">' + res[prop][i].description + '</label> <br />';
                     //      questions += '</div>';
                     //      questions += '<div class= "layout">';
                          
                     //      questions += '<label class="radio-inline"><span class="input-group-addon">' +
                     //                  '<input type="radio" class="'+ optional + '" name="' + res[prop][i].id +'" value="1" />'
                     //                  +"1</span></label>";
                                      
                                      
                     //      questions += '<label class="radio-inline"><span class="input-group-addon">' +
                     //                  '<input type="radio" class="'+ optional + '" name="' + res[prop][i].id +'" value="2" />'
                     //                  +"2</span></label>";
                                      
                     //      questions += '<label class="radio-inline"><span class="input-group-addon">' +
                     //                  '<input type="radio" class="'+ optional + '" name="' + res[prop][i].id +'" value="3" />'
                     //                  +"3</span></label>";
                                      
                                      
                     //      questions += '<label class="radio-inline"><span class="input-group-addon">' +
                     //                  '<input type="radio" class="'+ optional + '" name="' + res[prop][i].id +'" value="4" />'
                     //                  +"4</span></label>";
                                      
                                      
                     //      // questions += '<label class="radio-inline"><span class="input-group-addon">' +
                     //      //             '<input type="radio" class="'+ optional + '" name="' + res[prop][i].id +'" value="5" />'
                     //      //             +"5</span></label>";
                                      
                                      
                     //      questions += "<br />";
                     //      questions += "</div>"             
                     // }
                     // else
                     // {    
                     //      questions += '<div class="layout1">';
                     //      questions += '<label for="' + res[prop][i].id +'">' + res[prop][i].description + '</label> <br />';
                     //      questions += '</div>';
                     //      questions += createTextArea(res[prop][i].id) + "</div>";
                     //      questions += "<br />";
                     // }

                     
                     $("#category"+iterator).append($(questions));
                     questions = "";     
                  }   
              }
              
              iterator++;   
          }
      };
      
      var toggle = function(count, res, show) {
         var limit = count;
         var next = 1;
         var last;
         var obj = res;
         var id = new Array();
         var lastCategoryDom = show;
         var previousCategoryDom = null;
         var  show = show;
         
         for(prop in obj){
             if(obj.hasOwnProperty(prop))
             {
                 for(var i=0; i<res[prop].length; i++){
                 
                     if(res[prop][i].type === 'rating')
                     {
                         id.push(res[prop][i].id);
                     }
                 }
             }
         }
                           
         $("#next").click(function() {
              
              var question_show = 0;
              var checked = 0;
              
              previousCategoryDom = $(".previous");
              
              for(var i=0; i<id.length; i++)
              {
                  checked += show.find("input:radio[name=" + id[i] + "]:checked").length;
                  if(show.find("input:radio[name=" + id[i] + "]").length !== 0)
                  {   
                      if(show.hasClass('required-field'))
                        question_show += 1;
                  }
              }
              var checked2 = validateCurrentForm(show);

              if(checked !== question_show)
              {
                  alertify.alert('All radio buttons are required');
                  show = $(".show");
              }
               else if(!checked2){
                handleResult({
                  id : 'msg-container',
                  success: false,
                  msg: "Please fill-in the required fields."
                });
                return;
              }
              else
              {
                  next+=1;     
                  last = next - 1;
                  
                  $("#category"+last).addClass('previous');
                  $("#category"+(last-1)).removeClass('previous');
                  $("#category"+last).removeClass('show');
                  $("#category"+last).addClass('hide');
                  $("#category"+last).addClass('previous');
                  $("#category"+next).removeClass('hide');
                  $("#category"+next).addClass('show');                 
                  if(next === limit)
                  {
                      $("#next").prop('disabled', true);
                      $("#submit").prop('disabled', false);
                  }
                  $("#previous").prop('disabled', false); 
                  
                  show = null;
                  show = $(".show");

               }   
          });
          
          $("#previous").click(function() {
              
              $("#category"+last).removeClass('previous');
              $("#category"+(last-1)).addClass('previous');
              $("#category"+last).removeClass('hide');
              $("#category"+last).addClass('show');
              $("#category"+next).removeClass('show');
              $("#category"+next).addClass('hide');
              
              $("#submit").prop('disabled', true);
              next-=1;
              last = next - 1;

              if(last === 0)
              {
                  show = lastCategoryDom;
                  $("#previous").prop('disabled', true);
              }
              else
              {
                   show = previousCategoryDom;
              }
              $("#next").prop('disabled', false);
          });
          
          $("#submit").click(function(){
              var question_show1 = 0;
              var checked1 = 0;
              
              for(var i=0; i<id.length; i++)
              {
                  checked1 += show.find("input:radio[name=" + id[i] + "]:checked").length;
                  var current_input = show.find("input:radio[name=" + id[i] + "]")
                  if(current_input.length !== 0)
                  {   
                      if(show.hasClass('required-field'))
                        question_show1 += 1;
                  }
              }
              var checked2 = validateCurrentForm(show);
              if(!checked2){
                handleResult({
                  id : 'msg-container',
                  success: false,
                  msg: "Please fill-in the required fields."
                });
                return;
              }
              else
              {
                  alertify.confirm('Are you sure you want to submit the feedback ?', function(e){
                      console.log(e);
                      if(e)
                          $.ajax({
                              url: 'save_answers',
                              type: 'POST',
                              data: {'data': JSON.stringify($("#answers").serializeObject())},
                              success: function(res, textStatus, jqXhr){
                                  if(res.status === 'success')            
                                     window.location.href = window.location.href + 'thank_you'; 
                              }
                           });
                      else
                          return;
                  })
              }
          });      
    }
    
      $.fn.serializeObject = function()
      {
          var o = {};
          var a = this.serializeArray();
          $.each(a, function() {
              if (o[this.name] !== undefined) {
                  if (!o[this.name].push) {
                      o[this.name] = [o[this.name]];
                  }
                  o[this.name].push(this.value || '');
              } else {
                  o[this.name] = this.value || '';
              }
          });
          return o;
      };
      
      $.ajax({
          url: 'load_questions',
          type: 'GET',
          success: function (res, textStatus, jqXhr){
              displayQuiz(res);
              
              for(var i=Object.size(res); i>1; i--)
              {
                  $("#category"+i).addClass('hide');
                  $("#previous").prop('disabled', true);
                  $("#submit").prop('disabled', true);
              }
              $("#category"+1).addClass('show');
              
              var initShow = $('.show');
              toggle(Object.size(res), res, initShow);
          }  
      });
      
  });
 
  function clearError(){
    $(this).css('border', '1px solid #cccccc');
  }

  function clearRadio(){
    var parent = $(this).parents('.form-group')[0];
    $(parent).find('.radio-inline').css('background', 'none');
  }
  
})();
