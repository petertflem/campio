<?php
/*
*   replacePngTags - Justin Koivisto [W.A. Fisher Interactive] 7/1/2003 10:45AM
*   Modified: 5/11/2004 4:40PM
*
*   Modifies IMG and INPUT tags for MSIE5+ browsers to ensure that PNG-24
*   transparencies are displayed correctly.  Replaces original SRC attribute
*   with a transparent GIF file (spacer.png) that is located in the same
*   directory as the orignal image, and adds the STYLE attribute needed to for
*   the browser. (Matching is case-insensitive. However, the width attribute
*   should come before height.
*
*   Also replaces code for PNG images specified as backgrounds via:
*   background-image: url('image.png'); When using PNG images in the background,
*   there is no need to use a spacer.png image. (Only supports inline CSS at
*   this point.)
*
*   @param  $x  String containing the content to search and replace in.
*   @param  $img_path   The path to the directory with the spacer image relative to
*                       the DOCUMENT_ROOT. If none os supplied, the spacer.png image
*                       should be in the same directory as PNG-24 image. When supplying
*                       a path, be sure it ends with a '/'.
*   @result Returns the modified string.
*/
function replacePngTags($x,$img_path=''){
    $arr2=array();
    // make sure that we are only replacing for the Windows versions of Internet
    // Explorer 5+
    $msie='/msie\s([5-6])\.?[0-9]*.*(win)/i';
    if(!isset($_SERVER['HTTP_USER_AGENT']) ||
        !preg_match($msie,$_SERVER['HTTP_USER_AGENT']) ||
        preg_match('/opera/i',$_SERVER['HTTP_USER_AGENT']))
        return $x;

    // find all the png images in backgrounds
    preg_match_all('/background-image:\s*url\(\'(.*\.png)\'\);/Uis',$x,$background);
    for($i=0;$i<count($background[0]);$i++){
        // simply replace:
        //  "background-image: url('image.png');"
        // with:
        //  "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(
        //      enabled=true, sizingMethod=scale src='image.png');"
        // haven't tested to see if background-repeat styles work...
        $x=str_replace($background[0][$i],'filter:progid:DXImageTransform.'.
                'Microsoft.AlphaImageLoader(enabled=true, sizingMethod=scale'.
                ' src=\''.$background[1][$i].'\');',$x);
    }

    // OK, time to find all the IMG tags with ".png" in them
    $pattern='/<(input|img)[^>]*src=(\\\'|\\")([^>]*\.png)\2[^>]*>/i';
    preg_match_all($pattern,$x,$images);
    for($num_images=0;$num_images<count($images[0]);$num_images++){
        $original=$images[0][$num_images];
        $quote=$images[2][$num_images];
        $atts=''; $width=0; $height=0; $modified=$original;
        // If the size is defined by styles, find
        preg_match_all(
            '/style=(\\\'|\\").*(\s?width:\s?([0-9]+(px|%));).*'.
            '(\s?height:\s?([0-9]+(px|%));).*\\1/Ui',
            $images[0][$num_images],$arr2);
        if(is_array($arr2) && count($arr2[0])){
            // size was defined by styles, get values
            $width=$arr2[3][0];
            $height=$arr2[6][0];

            // remove the width and height from the style
            $stripper=str_replace(' ','\s','/('.$arr2[2][0].'|'.$arr2[5][0].')/');
            // Also remove any empty style tags
            $modified=preg_replace(
                '`style='.$arr2[1][0].$arr2[1][0].'`i',
                '',
                preg_replace($stripper,'',$modified));
        }
        // size was not defined by styles, get values
        preg_match_all('/width=(\\\'|\\")?([0-9%]+)\\1/i',$images[0][$num_images],$arr2);
        if(is_array($arr2) && count($arr2[0])){
            $width=$arr2[2][0];
            if(is_numeric($width))
                $width.='px';

            // remove this from the tag
            $modified=str_replace($arr2[0][0],'',$modified);
        }
        preg_match_all('/height=(\\\'|\\")?([0-9%]+)\\1?/i',$images[0][$num_images],$arr2);
        if(is_array($arr2) && count($arr2[0])){
            $height=$arr2[2][0];
            if(is_numeric($height))
                $height.='px';

            // remove this from the tag
            $modified=str_replace($arr2[0][0],'',$modified);
        }
        preg_match_all('/src=(\\\'|\\")([^\"]+\.png)\\1/i',$images[0][$num_images],$arr2);
        if(isset($arr2[2][0]) && !empty($arr2[1][0]))
            $image=$arr2[2][0];
        else
            $image=NULL;

        if(!empty($img_path)){
            // We do this so that we can put our spacer.png image in the same
            // directory as the image
            $tmp=split('[\\/]',$image);
            array_pop($tmp);
            $img_path=join('/',$tmp);
            if(strlen($img_path)) $img_path.='/';
        }

        // end quote is already supplied by originial src attribute
        $replace_src_with=$img_path.'spacer.png'.$quote.' style="width: '.$width.
            '; height: '.$height.'; filter: progid:DXImageTransform.'.
            'Microsoft.AlphaImageLoader(src=\''.$image.'\', sizingMethod='.
            '\'scale\');"';

        // now create the new tag from the old
        $new_tag=str_replace($image.$quote,$replace_src_with,
            str_replace('  ',' ',$modified));
        // now place the new tag into the content
        $x=str_replace($original,$new_tag,$x);
        $i++;
    }
    return $x;
}
?>
