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

use Components\Utils\Slug;

use Phalcon\Http\Request\File;
use Components\Library\Media\MediaFiles;
use Components\Library\Media\MediaType;

use Components\Model\Services\Service\Post as postService;
use Components\Model\Services\Service\Media as mediaService;


class Posts extends Model
{   
    
    use Timestampable;
    use SoftDeletable;

    public $type;

    public $id;

    const IMAGE_TYPE = 'image';
    const VIDEO_TYPE = 'video';
    //Such as pdf, docs, xls
    const DOCUMENT_TYPE   = 'document';




    const TYPE_POST = [
        'name' => 'Posts',
        'slug'  => 'post',
        'terms' => [ 'tag'],
        'metas' => [ 'seotitle'  => 'Seo title'  , 'seodesc' => 'Seo Description' ],
        'inputs' => ['title', 'slug' , 'body' , 'excerpt' , 'thumbnail'],
        'icon' => "paper-plane",
        'thumbnail' => true
    ];

    const TYPE_PAGE = [
        'name' => 'Pages',
        'slug' => 'page',
        'terms' => [],
        'metas' => [ 'seotitle'  => 'Seo title'  , 'seodesc' => 'Seo Description' ],
        'inputs' => ['title', 'slug' , 'body' , 'excerpt'],
        'icon' => "newspaper",
        'thumbnail' => false
    ];

    const TYPE_ATTACHMENT = [
        'name' => 'Media',
        'slug' => 'attachment',
        'terms' => [],
        'metas' => [],
        'inputs' => ['title'],
        'icon' => "image",
        'thumbnail' => false
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
    const STATUS_TRASH      = 'trash';
    const STATUS_DRAFT      = 'draft';

    const POST_STATUS = [
        self::STATUS_DRAFT      => self::STATUS_DRAFT,
        self::STATUS_PUBLISH    => self::STATUS_PUBLISH,
        self::STATUS_PENDING    => self::STATUS_PENDING,
        self::STATUS_TRASH      => self::STATUS_TRASH,
        self::STATUS_PRIVATE    => self::STATUS_PRIVATE,
    ];


    // This is column map and default post type
    const COLUMN_MAP = [ 
            "id",
            "title",
            "slug",
            "guid",
            "type",
            "body",
            "excerpt",
            "user_id",
            "status",
            "comment_statıs",
            "comment_count",
            "parent_id",
            "mime_type",
            "created_at",
            "updated_at",
            "deleted_at" 
            ];
    const DEFAULT_POST_TYPE = self::TYPE_PAGE;
    const DEFAULT_POST_STATUS = self::STATUS_DRAFT;


    /**
     * store error
     * @var array
     */
    protected $error;

    public function getSource()
    {
        return 'posts';
    }

    public function initialize()
    {  
        $this->fileSystem = new MediaFiles();
        $this->postService = new postService;
        $this->mediaService = new mediaService;

        $this->keepSnapshots(true);
        // if (auth()->isAuthorizedVisitor()) {
           // $this->addBehavior(
           //      new Blameable(
           //          [
           //              'auditClass'       => Audit::class,
           //          ]
           //      )
           //  );
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
        $this->hasMany('id', Posts::class, 'parent_id', ['alias' => 'children' ]);
        $this->belongsTo('parent_id', Posts::class, 'id', ['alias' => 'parent', 'reusable' => true]);

        
    }

 
    public function beforeValidationOnCreate()
    {
        // $this->status      = self::STATUS_DRAFT;
        $this->user_id = auth()->getUserId();

    }
     

    public function getId()
    {
      return $this->id;
    }

    public function setId($id)
    {
      $this->id = $id;
      return $this ;
    }

    public function getTitle()
    {
      return $this->title;
    }

    public function setTitle($title)
    {
      $this->title = $title;
      return $this ;
    }

    public function getSlug()
    {
      return $this->slug;
    }

    public function setSlug($slug)
    {
      $this->slug = $slug;
      return $this ;
    }

    public function getGuid()
    {
      return $this->guid;
    }

    public function setGuid($guid)
    {
      $this->guid = $guid;
      return $this ;
    }

    public function getType()
    {
      return $this->type;
    }

    public function setType($type)
    {
      $this->type = $type;
      return $this ;
    }

    public function getBody()
    {
      return $this->body;
    }

    public function setBody($body)
    {
      $this->body = $body;
      return $this ;
    }

    public function getExcerpt()
    {
      return $this->excerpt;
    }

    public function setExcerpt($excerpt)
    {
      $this->excerpt = $excerpt;
      return $this ;
    }

    public function getUserId()
    {
      return $this->user_id;
    }

    public function setUserId($user_id)
    {
      $this->user_id = $user_id;
      return $this ;
    }

    public function getStatus()
    {
      return $this->status;
    }

    public function setStatus($status)
    {
      $this->status = $status;
      return $this ;
    }

    public function getCommentStatıs()
    {
      return $this->comment_statıs;
    }

    public function setCommentStatıs($comment_statıs)
    {
      $this->comment_statıs = $comment_statıs;
      return $this ;
    }

    public function getCommentCount()
    {
      return $this->comment_count;
    }

    public function setCommentCount($comment_count)
    {
      $this->comment_count = $comment_count;
      return $this ;
    }

    public function getParentId()
    {
      return $this->parent_id;
    }

    public function setParentId($parent_id)
    {
      $this->parent_id = $parent_id;
      return $this ;
    }

    public function getMimeType()
    {
      return $this->mime_type;
    }

    public function setMimeType($mime_type)
    {
      $this->mime_type = $mime_type;
      return $this ;
    }

    public function getCreatedAt()
    {
      return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
      $this->created_at = $created_at;
      return $this ;
    }

    public function getUpdatedAt()
    {
      return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
      $this->updated_at = $updated_at;
      return $this ;
    }

    public function getDeletedAt()
    {
      return $this->deleted_at;
    }

    public function setDeletedAt($deleted_at)
    {
      $this->deleted_at = $deleted_at;
      return $this ;
    }


     
    /**
     * Get an error if occurred
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    private function setError($error)
    {
        $this->error[] = $error;
        return false;
    }
 
    /// media
    public function initFile(File $fileObj, $title = null )
    {   
        $fileExt     = $fileObj->getRealType();
        $mediaType   = new MediaType();

        // Check if file extension's allowed
        if (!$mediaType->checkExtension($fileExt)) {
            return $this->setError("Can't upload because file type's not allowed : ". $fileExt);
        }
        
        // generate path of file
        $originalname   =  $fileObj->getName();
        $filename       =  Slug::remove_special_chars( $fileObj->getName());
        $key            =  date('Y/m/') .  $filename ;
        $serverPath     =  resources_path( 'uploads/'. $key);
        $localPath      =  $fileObj->getTempName();
        
       
        if (!file_exists($localPath)) {
            return $this->setError("Can't find temp file for upload. This maybe caused by server configure");
        }
 
        if ($this->fileSystem->checkFileExists( 'resources/uploads/'. $key )) {

            $filename =  uniqid().  $filename ;
            $key = date('Y/m/').  $filename ;
            $serverPath = resources_path( 'uploads/'. $key);
        }

        

        if (!$this->fileSystem->uploadFile($localPath, 'resources/uploads/'. $key, $fileObj->getExtension())) {
            return $this->setError("Can't find temp file for upload. This maybe caused by server configure");
        }

       
        $meta['type'] = $fileExt;
        if ($mediaType->imageCheck($fileExt)) {
            $meta['type'] = self::IMAGE_TYPE;
            //@TODO add thumbnail
             $this->mediaService->generate_thumbnails($key,  $fileObj->getExtension());
        }
        $meta['title']  = $title ? $title : $originalname;
        $meta['slug']   = $filename;
        $meta['key']    = url()->get(('resources/uploads/'. $key));
        $uploadStatus   = $this->saveToDB($meta);
        if (!$uploadStatus) {
            return $this->setError(
                
                    'An error(s) occurred when uploading file(s), ' .
                    'Another file have same name with this file. Please change file name before upload'
                 
            );
        }

        return $meta;
        
 
    }

    /**
     * @param $key
     * @return bool
     */
    public function saveToDB($file)
    {
        $media = new Posts();
        $media->setTitle($file['title']);
        $media->setSlug($file['slug']);
        $media->setGuid($file['key']);
        $media->setType('attachment');
        $media->setUserId(  auth()->getUserId()  );
        $media->setStatus('inherit');
        $media->setMimeType($file['type']);
        if (!$media->save()) {
            return false;
            foreach ($media->getMessages() as $message) {
                return $message;
            }
        }
        return true;
    }

 

}