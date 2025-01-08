$(document).ready(function () {
    var myPlayer = videojs("video");
    var video_url = "";
    var modal = $('.modal');
    $('.video_preview').on('click', function (e) {
        e.preventDefault();
        var brightcove_id = $(this).data('videoid');
        $.get('/shows/videos/secure-video-url/'+brightcove_id, function(response){
            video_url = response;
        });
        modal.modal('show');
    });
    modal.on('shown.bs.modal', function () {
        console.log(video_url);
        myPlayer = videojs("video");
        myPlayer.src({"type": "video/mp4", "src": video_url});
        myPlayer.play();
    });
    modal.on('hidden.bs.modal', function () {
        myPlayer.pause();
    });
});
