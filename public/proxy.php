<?php

if($_REQUEST) {

    if($_REQUEST['type'] == 'video') {
        header('Accept-Ranges: bytes');
        header('Content-Range: bytes=0-'.(intval($_REQUEST['size'])-1));
        //header('Content-Length: '.$_REQUEST['length']);
        header('Connection:Keep-Alive');
        header("Content-type: video/mp4"); //Desktop Computers
        if(isset($_REQUEST['filename'])) header('Content-disposition: attachment; filename="'.$_REQUEST['filename'].'.mp4"');

    }
    else if ($_REQUEST['type']=='image')
    {
        header('Accept-Ranges: bytes');
        header('Connection:Keep-Alive');
        header("Content-type: image/jpeg"); //Desktop Computers
        if(isset($_REQUEST['filename'])) header('Content-disposition: attachment; filename="'.$_REQUEST['filename'].'.jpg"');

    }
    if(isset($_REQUEST['url']))
        echo file_get_contents($_REQUEST['url']);
    else if($_REQUEST['decode'])
    {
        if(isset($_REQUEST['filename'])) header('Content-disposition: attachment; filename="'.$_REQUEST['filename'].'.png"');
        header('Accept-Ranges: bytes');
        header('Connection:Keep-Alive');
        header('Content-Description: File Transfer');
        header("Content-type: application/octet-stream");
        exit(base64_decode($_POST['image'])); //url length is limited, use post instead
    }
}
else die('no access');
?>