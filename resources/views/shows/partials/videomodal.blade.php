<style>
    .modal.fade .modal-dialog {
        -webkit-transition: -webkit-transform .3s ease-out;
        -moz-transition: -moz-transform .3s ease-out;
        -o-transition: -o-transform .3s ease-out;
        transition: transform .3s ease-out;
        -webkit-transform: translate(0, -25%);
        -ms-transform: translate(0, -25%);
        /* transform: translate(0, -25%); */
    }
    .modal.in .modal-dialog {
        -webkit-transform: translate(0, 0);
        -ms-transform: translate(0, 0);
        /* transform: translate(0, 0); */
    }
    .modal-dialog {
        margin-top: 0;
        margin-bottom: 0;
        width:50%;
        height: 100vh;
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -webkit-flex-direction: column;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: center;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
    }
    .modal-content{
        background-color:rgba(0,0,0,0.8);
    }

    .modal.fade .modal-dialog {
        -webkit-transform: translate(0, -100%);
        transform: translate(0, -100%);
    }
    .modal.in .modal-dialog {
        -webkit-transform: translate(0, 0);
        transform: translate(0, 0);
    }
    .close{
        color:#000;
        opacity:1;
    }

</style>
{{-- Modal Video Player Window --}}
<div class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="pull-right">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" aria-hidden="true"><i class="fa fa-times-circle-o"></i>
        </div>
        <div class="modal-content">
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <video id="video" data-account="3670015105001"
                           data-player="rJoSfgYW"
                           data-embed="default"
                           class="video-js embed-responsive-item"
                    controls>
                        Your Browser does not support HTML5 Video
                    </video>
                    <script src="//players.brightcove.net/3670015105001/rJoSfgYW_default/index.min.js"></script>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- /.Modal Video Player Window --}}