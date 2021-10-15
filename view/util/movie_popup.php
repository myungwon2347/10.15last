<style>
iframe,embed,object {max-width: 100%;}
video { max-width: 100%; height: auto;}
.fd-modal { position: fixed; left: 0; bottom: 100%; min-height: 100%; width: 100%; z-index: 500000; text-align: center; background: rgba(0, 0, 0, 0.7);}
.csstransforms #fd-modal-overlay { transition: transform 200ms cubic-bezier(0.48, 0.01, 0.48, 0.99);}
.no-csstransforms #fd-modal-overlay {  transition: 200ms all cubic-bezier(0.48, 0.01, 0.48, 0.99);}
.csstransforms #fd-modal-overlay.opened { transform: translateY(100%) translateZ(0);}
.csstransforms #fd-modal-overlay.closed { transform: translateY(0) translateZ(0);}
.no-csstransforms #fd-modal-overlay.opened { bottom: 0;}
.fd-video-wrapper { padding: 1.25rem; position: relative;}
.fd-close.fd-hide-modal { text-align: right; width: 100%; display: block; position: absolute; top: -10px; right: 32px;}
#fd-close-anchor { text-decoration: none; color: #000;}
.fd-inner { display: none; margin: 0 auto; max-width: 880px; padding: 20px; position: absolute; top: 50%; width: 100%;}
.no-csstransforms .fd-inner { height: 281px; margin-top: -140px; margin-left: 250px;}
.csstransforms .fd-inner { left: 50%; width: 100%; transform: translate(-50%, -50%);}
</style>

<!-- Video Modal -->
<div id="fd-modal-overlay" class="fd-modal">
    <div class="fd-inner">
        <div class="fd-video-wrapper">
            <a href="#" class="fd-close fd-hide-modal" id="fd-close-anchor"></a>
            <iframe src="" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="fd-video"></iframe>
        </div>
    </div>
</div>

<script>
//===================================
// Video Modal Variables
//===================================

var fdVideo = $('#fd-video');


//===================================
// Froogaloop
//===================================

function playFDModalVid(playerID) {
  var player = $f(playerID.get(0));

  player.addEvent('ready', function(progress) {
      player.api('play');
  });
}

function pauseFDModalVid(playerID) {
  var player = $f(playerID.get(0));

  player.addEvent('ready', function() {
      player.api('pause');
  });
}


//===================================
// Video Modal Events
//===================================

function openVideoModal(event) {
  event.preventDefault();

  var duration = 200,
      modalId = $(this).attr('href'),
      modal = $(modalId),
      inner = modal.find(".fd-inner");

  $('#fd-close-anchor').text('✕');

  $('#fd-video').attr(
    'src',
    'https://player.vimeo.com/video/214092083'
  );

  $(".fd-inner").fitVids({
    customSelector: "iframe[src^='https://player.vimeo.com']"
  });

  modal.removeClass('closed').addClass('opened');

  setTimeout(function() {
    inner.fadeIn(duration, function() {
      playFDModalVid(fdVideo);
    });
  }, duration);
}


function closeVideoModal(event) {
  event.preventDefault();

  var duration = 200,
      modal = $(this).closest(".fd-modal"),
      inner = modal.find(".fd-inner");

  inner.fadeOut(duration, function() {
    pauseFDModalVid(fdVideo);
    modal.removeClass('opened').addClass('closed');
  });
}


//===================================
// Video Modal Triggers
//===================================

$('.fd-show-modal').on('click', openVideoModal);
$('.fd-show-modal').on('touchstart', openVideoModal);

$('#fd-modal-overlay').on('click', closeVideoModal);
$('#fd-modal-overlay').on('touchstart', closeVideoModal);
</script>

<script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/392/jquery.fitvids.js"></script>
<script src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>