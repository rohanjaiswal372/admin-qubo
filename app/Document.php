<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;
use File;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';

    public function getDiskConfigurationAttribute() {
        return config("filesystems.disks.rackspace");
    }

    public function getUrlAttribute($value){
        return (filter_var($value, FILTER_VALIDATE_URL)) ? $value : config('filesystems.disks.rackspace.public_url_ssl').'/'.$value;
    }

    public static function upload($data) {
        $disk = Storage::disk("rackspace");
        $allowed_ext =  config('document.document_types');
        $result = NULL;
        $extension = (isset($data['extension']))? $data['extension'] : 'txt';

        if(isset($data['file'])) {
            if (in_array($extension,$allowed_ext )) {
                $result = $disk->put($data['destination'], File::get($data['file']));
            }
        } else return FALSE;

        if ($result) {
            $document = new Document;
            $document->documentable_id = $data['object_id'];
            $document->documentable_type = $data['model'];
            $document->extension = $extension;
            $document->name = $data['filename'];
            $document->filename = $data['filename'];
            $document->path = $data['destination'];
            $document->save();
            return $document;
        }
        else return false;
    }

    public function getFileTypeAttribute(){
        if(!is_null(File::extension($this->url)))
            return File::extension($this->url);
        else return 'jpg';
    }

    public function getIconAttribute(){
        $ext = $this->extension;
        switch($ext){
            case 'docx':
            case 'doc':
                return "fa-file-word-o text-primary";
            case "pdf":
                return "fa-file-pdf-o text-danger";
            case "eps":
            case "ai":
                return "fa-file-image-o text-warning";
            case "xls":
            case "xlsx":
                return "fa-file-excel-o text-success";
            default:
                return "fa-file-archive-o";
                break;
        }
    }

}
