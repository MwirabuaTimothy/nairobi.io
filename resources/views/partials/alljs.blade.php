<script type="text/javascript">

  $.fn.serializeObject = function() //for forms
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

  loadMap = function(_div, _path){
    console.log('Loading map for: ' + _path)
    myIcon = L.icon({ // needs to be initialized globally so that it can be referred
      iconUrl: pin_pink,
      iconSize: [20, 20],
      iconAnchor: [10, 10],
      labelAnchor: [6, 0] // as I want the label to appear 2px past the icon (10 + 2 - 6)
    });
    map = L.map(_div, { // needs to be initialized globally so that it can be referred
      scrollWheelZoom: false,
      zoomControl: true,
      attributionControl: false,
      }).setView([51.505, -0.09], 5); 

    L.tileLayer(
    'http://{s}.tile.cloudmade.com/5e9427487a6142f7934b07d07a967ba3/997/256/{z}/{x}/{y}.png', {
        // attribution: '',
        maxZoom: 18,
        // noWrap: true,
        // opacity: 0.5
      }).addTo(map);
  }


  mapRecord = function(_record){
    coords = _record.map;
    // console.log(_record);
    mlat = parseFloat(coords.substr(0, coords.indexOf(', ')))
    mlng = parseFloat(coords.substr(coords.indexOf(', ')+2, coords.length))
    
    _recordName = _record.name
    marker = 
    L.marker([mlat, mlng], {
          icon: myIcon
          })
    var popup = L.popup()
        .setContent('<a href="/'+_path+'">'+_recordName+'</a>');
    setTimeout(function(){ popup.openOn(marker);}, 2000);
    marker.bindPopup(popup)
    marker.addTo(map);

    markersgroup.push([mlat, mlng])
    setTimeout(function(){ map.fitBounds(markersgroup) }, 500)
    
  }

  onMapClick = function(e) {
    lat = e.latlng.lat
    lng = e.latlng.lng
    pin2map(lat, lng)
  }

  pin2map = function(lat, lng){
    var myIcon = L.icon({
      iconUrl: pin_green,
      iconSize: [20, 20],
      iconAnchor: [10, 10],
      labelAnchor: [6, 0] 
    });
    marker_new
      .setLatLng({'lat': lat, 'lng': lng})
      .setIcon(myIcon)
      .addTo(map);
    $('.coords').val(lat+', '+lng)

  }

  loggedIn = function(){
    if('{{ Sentry::check() }}' == '1'){
      return true;
    }
    else{
      return false;
    }
  }

  isMain = function(){
    if($.inArray(_path, ['.', 'users', 'eventts']) > -1){
      return true
    }
    return false
  }

  clickLogger = function(){
    $(document).bind('click', function(e) {
      // console.log(this.className + ' clicked')
      // console.log(this)
      // console.log($(this))
      // console.log(e)
      // console.log(e.target)
      console.log('Clicked: '+e.target.tagName +'#' + e.target.id + ' .' + e.target.className)

    })
  }

  countUp = function(display){
    var count = display.data('count');
    console.log(count);
    var div_by = 100,
        speed = Math.round(count / div_by),
        run_count = 1,
        int_speed = 24;

    var int = setInterval(function() {
      if(run_count < div_by){
          display.text(speed * run_count);
          run_count++;
      } else if(parseInt(display.text()) < count) {
          var curr_count = parseInt(display.text()) + 1;
          display.text(curr_count);
      } else {
          clearInterval(int);
      }
    }, int_speed);
  }

window.onload = initStyle;
window.unload = initStyle;
    
function initStyle() {

  if($('.alert')){
  $('.alert').slideToggle(1000); 
  setTimeout(function(){$('.alert').slideToggle(1000)}, 5000);
  }

  //$("img.preload").fadeOut(500, function() {});
};
//});​

var _url = window.location.href;
var _host = window.location.host;
var _path = window.location.pathname;
_path = _path.substr(1, _path.length)
if(_path ==''){_path = '.'}
var _model = _path.substr(0, _path.indexOf('/'))
var _id = _path.substr(_path.indexOf('/')+1, _path.length1)
// console.log(_path);
var _record = {}
var pin_pink = '{{ asset("images/mapping/bubble-pink.png") }}'
var pin_green = '{{ asset("images/mapping/left-dex-green.png") }}'
var marker_new = L.marker()
var markersgroup = []

$(document).ready(function(){
  // clickLogger();

  $.each($('._search select'), function(){
    x = $(this)
    x.val(x.data('selected'))
  })

  var hash = window.location.hash;
  if(hash != ''){
    $('.tab-content .active').removeClass('active')
    $(hash).addClass('active') //for companies, users, maps
    $('.nav-tabs li').removeClass('active')
    $('li a[href="'+hash+ '"]').parent().addClass('active')

  }
  hash && $('ul.nav a[href="' + hash + '"]').tab('show');
  $('.nav-tabs a').click(function (e) {
    $(hash).addClass('active') //for companies, users, maps
    $(this).tab('show');
    var scrollmem = $('body').scrollTop();
    window.location.hash = this.hash;
    $('html,body').scrollTop(scrollmem);
  });


  prev_img = $('.fileupload-new img').attr('src');
    // $('.fileupload.fileupload-exists a.btn-danger').on('click', function(){
    //   $('.fileupload-exists img').remove();
    //   $('.fileupload-new').show();
    // });

  if($('#company-map')[0] != undefined){
    loadMap('company-map', _path)

    _record = $('#company-map').data('model')
    // console.log(_record);
    if(_record.map != ''){ //if coords are not set yet, dont map!
      mapRecord(_record);
    }
    
    // enable drag and zoom handlers
    map.on('focus', function(){
      map.scrollWheelZoom.enable();
    })
    if($('.edit #company-map')[0] != undefined){
      map.on('click', onMapClick);
    }
    // $('a[href="#map"').click(function(){
    //   map.fitBounds(markersgroup)
    // })    
  }

  $('._count-up').each(function(){ countUp($(this))})

  $('.symbol .fa-star').on('click', function(d){
    $.post(window.location.pathname+'/star', function(res){
        if(res['starred']){
          d.target.id = 'starred-1';
          counter = d.target.parentNode.parentNode.children[1].children[0];
          counter.innerHTML = parseInt(counter.innerHTML)+1;
        }else{
          d.target.id = 'starred-0';
          counter = d.target.parentNode.parentNode.children[1].children[0];
          counter.innerHTML = parseInt(counter.innerHTML)-1;
        }
      })
  })

  if($('input.search-cats')[0] != undefined){
    $('input.search-cats').quicksearch('ul.prod-cat li');
  }


  // confirm before deleting
  if($.fn.confirmation != undefined && _path.indexOf('/create') == -1){ //dont confirm deletions on /create paths
    $('.btn-danger, ._del').click(function(e){
      e.preventDefault();
    });
    
    $('.btn-danger, ._del').not('.btn-danger.fileupload-exists').confirmation({
        'href' : './delete',
        'onCancel' : function(){
          $('.popover').fadeOut(200);
        }
    })
  }

  //frontend common-scripts.js

  if($.fn.customSelect != undefined){
    $('select').customSelect();
  }

  //    tool tips
  $('.tooltips').tooltip();

  //    popovers

  $('.popovers').popover();

  //    bxslider

  if($.fn.bxslider != undefined){
    $('.bxslider').show();
    $('.bxslider').bxSlider({
        minSlides: 4,
        maxSlides: 4,
        slideWidth: 276,
        slideMargin: 20
    });
  }

// custom scrollbar
    $("#sidebar").niceScroll({styler:"fb",cursorcolor:"#e8403f", cursorwidth: '3', cursorborderradius: '10px', background: '#404040', spacebarenabled:false, cursorborder: ''});

    $("html").niceScroll({styler:"fb",cursorcolor:"#ff7261", cursorwidth: '6', cursorborderradius: '10px', background: 'transparent', spacebarenabled:false,  cursorborder: '', zindex: '1000', autohidemode: false});

    $("._cats .panel-body").niceScroll({styler:"fb",cursorcolor:"#ff7261", cursorwidth: '10', cursorborderradius: '10px', background: '#fff', spacebarenabled:false,  cursorborder: '', zindex: '1000', railalign: 'left', autohidemode: false, horizrailenabled:false});

  //backend common-scripts.js

  //wysihtml5 rich Editor
  if($.fn.wysihtml5 != undefined){
    $('.rich').wysihtml5();
    $('form input[name="_wysihtml5_mode"]').remove()
  }

  $('.btn.email').click(function(e){
    $('.modal.inquire .modal-title.inquire').html($(e.target).data('modal-title'))
    $('.modal.inquire .to').val($(e.target).data('modal-to'))
    $('.modal.inquire').modal('show')
  });
  $('.btn.email.product').click(function(e){
    $('.modal.inquire .subject').val($(e.target).data('modal-title'))
  });

  if(!loggedIn()){
    $('.modal.inquire .signedin').hide()
    $('.modal.inquire .signin').show()

    $('.modal.inquire').submit(function(e){
      e.preventDefault();
      msg = $(e.target).serializeObject();
      $.post('/inquire', msg);
      $('.modal.inquire').modal('hide')
      $('.modal.signin').modal('show')
    })
  }

  $('.modal.signup form').on('submit', function(e){
    e.preventDefault()
    $('.modal.signup form input[type="submit"]').attr('disabled', 'on');
    $.post($(this).attr('action'), $(this).serializeArray(), function(ddd){
      // console.log(JSON.stringify(ddd))
      if(ddd['success']){
        $('.modal.signup').modal('hide')
        alert('Successful! Check ' 
          + $('.modal.signin form input[name="email"]').val() 
          + ' for an Activation Code.')
        // window.location.reload(); 
      }
      else{
        $('.modal.signup form input[type="submit"]').removeAttr('disabled');
        $('p._pink').remove()
        $.each(ddd['errors'], function(key, value) {
          $('<p class="_pink _top10">'+value+'</p>').insertBefore(
            $('.modal.signup form input[name="'+key+'"]'))
        })
      }
    })
  })

  $('.modal.signin form').on('submit', function(e){
    e.preventDefault()
    $('.modal.signin form input[type="submit"]').attr('disabled', 'on');
    $('img.preload').show()
    $.post($(this).attr('action'), $(this).serializeArray(), function(ddd){
      // console.log(JSON.stringify(ddd))
      if(ddd['success']){
          window.location.pathname = ddd['redirect']; 
        }
        else{
          $('.modal.signin form input[type="submit"]').removeAttr('disabled');
          $('p._pink').remove()
          $.each(ddd['errors'], function(key, value) {
            $('<p class="_pink _top10">'+value+'</p>').insertBefore(
              $('.modal.signin form input[name="'+key+'"]'))
          })
          $('<p class="_pink _top10">Please check your details...</p>').insertBefore(
            $('.modal.signin form input[name="email"]'))
        }
      })
    })



  $('.get-signin').click(function(e){ 
    e.preventDefault()
    $('.modal.signup').modal('hide')
    $('.modal.signin').modal('show')
  })

  $('.get-signup').click(function(e){ 
    e.preventDefault()
    $('.modal.signin').modal('hide')
    $('.modal.signup').modal('show')
  })

})

</script>