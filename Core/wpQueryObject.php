<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/09/26
 * Time: 11:15 PM
 */
class wpQueryObject
{

    private $queryArgs = [];
    private $postType;

    function __construct($postType)
    {
        $this->postType = $postType;
    }

    #TODO and column logic
    public function Select($colums = [])
    {
        $queryArgs["post_type"] =  $this->postType->GetSlug();

        return $this;
    }

    public function OrderBy($fieldname, $asc_desc)
    {
        #TODO: What if not meta Value
        $queryArgs["orderby"] =  "meta_value";
        $queryArgs["meta_key"] =  $fieldname;
        $queryArgs["order"] =  $asc_desc; # ASC or DESC
        return $this;

    }

    public function Fetch()
    {
        $loop = new WP_Query( $this->queryArgs );

        while ( $loop->have_posts() ) : $loop->the_post();

            $returnRow = [];

            foreach ($this->postType->fields as $field)
            {
                #TODO: if not custom post
                $returnRow[$field->id] = post_custom($field->valueKey);
            }
            yield  $returnRow;

        endwhile;
    }
}