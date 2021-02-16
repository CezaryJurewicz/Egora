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
    return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?[^"<>\.\s]+)?[^"<>\.\s])?)?)@', '<a href="$1" rel="nofollow" target="_blank">$1</a>', $text);
}

function ip_used_places($ideas, $idea=null) {
    $numbered = [];
    $current_idea_position = null;
    foreach($ideas as $i) 
    {
        $position = ($i->pivot) ? $i->pivot->order: $i->position;
        $numbered[] = $position;

        if($idea && $i->id == $idea->id) {
            $current_idea_position = $position;
        }
    }

    return [$numbered, $current_idea_position];
}

function ip_places() {
    $places = [];
    
    for($i=46;$i>=1;$i--){
        $places[] = $i;
    }
    
    return $places;
}

function ip_has_place($ideas, $idea) {
    $all = ip_places();
    list($used, $current) = ip_used_places($ideas,$idea);
    $unused = array_diff($all, $used);
    
    $up = false;
    $down = false;
    
    foreach($unused as $place) {
        $up = $place > $current ?: $up;
        $down = $place < $current ?: $down;
    }
    
    return [$up, $down];
}

function current_egora_id() {
    return config(implode('.', ['egoras', session('current_egora', 'default'), 'id']));
}

function redirect_to_egora_home() {
    return redirect()->guest(config(implode('.', ['egoras', session('current_egora', 'default'), 'redirect'])));
}

function is_egora($egora = 'default') {
    return config('egoras.'.$egora.'.id') == current_egora_id();
}

function previous_route() {
    return app('router')->getRoutes()->match(app('request')->create(url()->previous()));
}

function header_bg_color() {
    if (session('current_egora', 'default') == 'default' 
        &&  auth('web')->check() 
        && auth('web')->user() 
        && (auth('web')->user()->user_type->class == 'user' || auth('web')->user()->user_type->former)
        ) {
        return "#636363";
    }
    
    return config(implode('.',['egoras',session('current_egora', 'default'),'bgcolor']));
}