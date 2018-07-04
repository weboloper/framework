<?php
namespace Components\Model;

use Components\Model\Traits\Timestampable;
use Components\Model\Traits\SoftDeletable;

use Components\Model\TermRelationships;
use Components\Model\Terms;
use Components\Model\PostMeta;
use Components\Model\Users;

use Phalcon\Mvc\Model\Behavior\Blameable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

use Components\Model\Audit;



use Phalcon\Http\Request\File;
use Components\Library\Media\MediaFiles;
use Components\Library\Media\MediaType;

class Posts extends Model
{
    use Timestampable;
    use SoftDeletable;

    public $type;

    const TYPE_POST = [
        'name' => 'Posts',
        'slug'  => 'post',
        'terms' => [ 'tag' , 'category', 'format'],
        'metas' => [ 'seo_title'  => 'seo_title'  , 'seo_desc' => 'seo_desc'],
        'inputs' => ['title', 'body' , 'excerpt'],
        'icon' => "paper-plane",
    ];

    const TYPE_PAGE = [
        'name' => 'Pages',
        'slug' => 'page',
        'terms' => [],
        'metas' => [ 'seo_title'  => 'seo_title'  , 'seo_desc' => 'seo_desc'],
        'inputs' => ['title', 'body' , 'excerpt'],
        'icon' => "newspaper",
    ];

    const TYPE_ATTACHMENT = [
        'name' => 'Attachments',
        'slug' => 'attachment',
        'terms' => [],
        'metas' => [],
        'inputs' => ['title'],
        'icon' => "image",
    ];

    #register post types
    const POST_TYPES = [
        self::TYPE_POST['slug'] =>  self::TYPE_POST,
        self::TYPE_PAGE['slug'] =>  self::TYPE_PAGE,
        self::TYPE_ATTACHMENT['slug'] =>  self::TYPE_ATTACHMENT,
    ];


    const STATUS_PRIVATE    = 'private';
    const STATUS_PUBLISH    = 'publish';
    const STATUS_PENDING    = 'pending';
    const STATUS_FUTURE     = 'future';
    const STATUS_TRASH      = 'trash';
    const STATUS_DRAFT      = 'draft';

    const POST_STATUS = [

        self::STATUS_DRAFT      => self::STATUS_DRAFT,
        self::STATUS_PUBLISH    => self::STATUS_PUBLISH,
        self::STATUS_PENDING    => self::STATUS_PENDING,
        self::STATUS_FUTURE     => self::STATUS_FUTURE,
        self::STATUS_TRASH      => self::STATUS_TRASH,
        self::STATUS_PRIVATE    => self::STATUS_PRIVATE,
 

    ];


    public function getSource()
    {
        return 'posts';
    }

    public function initialize()
    {  
        $this->fileSystem = new MediaFiles();

        $this->keepSnapshots(true);
        // if (auth()->isAuthorizedVisitor()) {
           $this->addBehavior(
                new Blameable(
                    [
                        'auditClass'       => Audit::class,
                    ]
                )
            );
        // }

        $this->addBehavior(
            new SoftDelete(
                [
                    "field" => "status",
                    "value" =>  "trash",
                ]
            )
        );


        $this->hasManyToMany(
            'id',
            TermRelationships::class,
            'post_id',
            'term_id',
            Terms::class,
            'term_id',
            ['alias' => 'terms']
        );

        $this->hasMany('id', PostMeta::class, 'post_id', ['alias' => 'meta', 'reusable' => true]);
        $this->belongsTo('user_id', Users::class, 'id', ['alias' => 'user', 'reusable' => true]);
    }

    public function beforeValidationOnCreate()
    {
        // $this->status      = self::STATUS_DRAFT;

    }
    /**
     * Implement hook beforeCreate
     *
     * Create a posts-views logging the ipaddress where the post was created
     * This avoids that the same session counts as post view
     */
    public function beforeCreate()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    public function getExcerpt()
    {
        return $this->excerpt;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setParent_id($parent_id)
    {
        $this->parent_id = $parent_id;
        return $this;
    }

    public function getParent_id()
    {
        return $this->parent_id;
    }


 
    /// media
    public function initFile(File $fileObj)
    {   

        $fileExt     = $fileObj->getRealType();
        $mediaType   = new MediaType();
 
        // Check if file extension's allowed
        if (!$mediaType->checkExtension($fileExt)) {
            return $this->setError(t("Can't upload because file type's not allowed"). ": ". $fileExt);
        }

      
        // generate path of file
        $key = date('Y/m/') .  $fileObj->getName();
        $serverPath = sandbox_path('uploads/' . $key);
        $localPath = $fileObj->getTempName();
        

        if (!file_exists($localPath)) {
            return $this->setError(t("Can't find temp file for upload. This maybe caused by server configure"));
        }

        if ($this->fileSystem->checkFileExists($serverPath)) {
            return $this->setError(
                t(
                    'An error(s) occurred when uploading file(s), ' .
                    'Another file have same name with this file. Please change file name before upload'
                )
            );
        }

        if (!$this->fileSystem->uploadFile($localPath, 'uploads/'. $key, $fileObj->getExtension())) {
            return $this->setError(t("Can't find temp file for upload. This maybe caused by server configure"));
        }

        $meta['type'] = null;
        if ($mediaType->imageCheck($fileExt)) {
            // $meta['type'] = self::IMAGE_TYPE;
            //@TODO add thumbnail
        }
        $meta['title'] = $fileObj->getName();
        $meta['key']  = $key;
        $uploadStatus = $this->saveToDB($meta);
        if (!$uploadStatus) {
            return "error";
        }
        return '/uploads/'. $key;
 
    }

    /**
     * @param $key
     * @return bool
     */
    public function saveToDB($file)
    {
        $media = new Posts();
        $media->setTitle($file['title']);
        $media->setSlug('uploads/'.$file['key']);
        $media->setType('attachment');
        $media->setUserId(  auth()->getUserId()  );
        $media->setStatus('inherit');
        if (!$media->save()) {
            return false;
            foreach ($media->getMessages() as $message) {
                return $message;
            }
        }
        return true;
    }


}