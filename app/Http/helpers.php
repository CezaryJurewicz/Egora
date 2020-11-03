<?php

function shorten_text($text, $limit = 92) 
{

    if (str_word_count($text, 0) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $result = substr($text, 0, $pos[$limit]);
        
        if (strrpos($result, '.') || strpos($text, '.', $pos[$limit])) {
            $backward = $pos[$limit] - strrpos($result, '.');
            $forward = abs(strpos($text, '.', $pos[$limit]) - $pos[$limit]);
            
            if ($backward < $forward) {
                $result =  substr($text, 0, $pos[$limit] - ($backward-1) );
            } else {
                $result =  substr($text, 0, $forward + ($pos[$limit]+1) );
            }
        }
    } else {
        $result = $text;
    }
    
    return strip_tags(
               nl2br(
                   str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 
                      $result 
                   )
               ), '<br><p><b><i><li><ul><ol>'
        );

}

function make_clickable_links($text) {
  return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
}