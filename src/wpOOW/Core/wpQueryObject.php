<?php

/**
 * Class wpQueryObject
 * Creates a query object that acts as a wrapper class to create wp queries with relevant properties for a post types
 * @package wpAPI\Core
 *
 */
class wpQueryObject
{


    private $queryArgs = [];

    private $postType;

    /**
     * wpQueryObject constructor.
     * @param $postType
     */
    function __construct($postType)
    {
        $this->postType = $postType;
    }

    #TODO and column logic
    /**
     * Set this initial properties for the query
     * @param array $colums
     * @return $this
     */
    public function Select($colums = [])
    {
        $this->queryArgs["post_type"] =  $this->postType->GetSlug();
        $this->queryArgs["posts_per_page"] = -1;

        return $this;
    }

    /**
     * Set the return order of the data
     * 
     * @param $fieldname
     * @param $asc_desc
     * @param bool $use_numbers
     * @return $this
     */
    public function OrderBy($fieldname, $asc_desc, $use_numbers=false)
    {
        #TODO: What if not meta Value
        $this->queryArgs["orderby"] =  $use_numbers ? "meta_value_num" : "meta_value";
        $this->queryArgs["meta_key"] =  $this->postType->GetFieldDbKey($fieldname);
        $this->queryArgs["order"] =  $asc_desc; # ASC or DESC
        return $this;

    }

    /**
     * Returns a generator that access the data for a posttype with relevant properties 
     * @return Generator
     */
    public function Fetch()
    {
        $loop = new WP_Query( $this->queryArgs );

        while ( $loop->have_posts() ) : $loop->the_post();

            $returnRow = new PostTypeRow($this->postType->GetSlug());

            foreach ($this->postType->GetFields() as $field)
            {
                #TODO: if not custom post
                $returnRow[$field->id] = $field->FormatForFetch(get_post_meta(get_the_ID(),$field->valueKey, true), get_the_ID());
            }
            yield  $returnRow;

        endwhile;
    }
}


/**
 * Class PostTypeRow
 */

#TODO: Really think about this. The proper way of implementing. The class is no longer generic
class PostTypeRow extends ArrayObject
{

    private $parent_slug = "";
    private $storage = array();

    //https://gist.github.com/eaglstun/1100119


    public function __construct($parent_slug = "")
    {
        $this->parent_slug = $parent_slug;
        parent::setFlags(parent::ARRAY_AS_PROPS);
        parent::setFlags(parent::STD_PROP_LIST);
    }

    public function __get($k)
    {

        return isset($this->storage[$this->parent_slug .'_'.$k]) ? $this->storage[$this->parent_slug .'_'.$k] : FALSE;
    }


    public function offsetGet($k)
    {
        return isset($this->storage[$this->parent_slug .'_'.$k]) ? $this->storage[$this->parent_slug .'_'.$k] : FALSE;
    }


    public function __set($k, $v)
    {
        $this->storage[$k] = $v;
    }


    public function offsetSet($k, $v)
    {
        is_null($k) ? array_push($this->storage, $v) : $this->storage[$k] = $v;
    }


    public function count()
    {
        return count($this->storage);
    }

    public function asort()
    {
        asort($this->storage);
    }


    public function ksort()
    {
        ksort($this->storage);
    }

    public function offsetUnset($name)
    {
        unset($this->storage[$name]);
    }


    public function __unset($name)
    {
        unset($this->storage[$name]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->storage);

    }
}