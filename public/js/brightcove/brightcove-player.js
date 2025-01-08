$('.video_preview').on('click', function (e) {
    e.preventDefault();
    brightcove_id = $(this).data('videoid');
    $modal = $('.modal');
    $modal.modal('show');

});
var player,
    APIModules,
    videoPlayer,
    brightcove_id;
function onTemplateLoad(experienceID){
    player = brightcove.api.getExperience(experienceID);
    APIModules = brightcove.api.modules.APIModules;
}
function onTemplateReady(evt){
    videoPlayer = player.getModule(APIModules.VIDEO_PLAYER);
    videoPlayer.loadVideoByID(brightcove_id);
    videoPlayer.play();
}


