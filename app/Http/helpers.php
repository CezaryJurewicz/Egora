<?php


function _shorten_text($text, $limit = 92) 
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
    
    return $result;
}

function _shorten_text_characters($text, $limit = 92) 
{ 
    if (strlen($text) > $limit) {
        $words = str_word_count($text, 2);
        $pos = array_keys($words);
        $result = substr($text, 0, $limit);
        
        if (strrpos($result, ' ') || strpos($text, ' ', $limit)) {
            $backward = $limit - strrpos($result, ' ');
            $forward = abs(strpos($text, ' ', $limit) - $limit);
            
            if ($backward < $forward) {
                $result =  substr($text, 0, $limit - ($backward-1) );
            } else {
                $result =  substr($text, 0, $forward + ($limit+1) );
            }
        }
    } else {
        $result = $text;
    }
    
    return rtrim($result,".,?!: ");
}

function shorten_text($text, $limit = 92) 
{
   
    return strip_tags(
               nl2br(
                   str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 
                      htmlspecialchars(_shorten_text($text, $limit))
                   )
               ), '<br><p><b><i><li><ul><ol>'
        );

}

function shorten_text_link($text, $limit = 42) 
{
   
    return  nl2br(
                str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 
                     make_clickable_links(
                         htmlspecialchars(
                             _shorten_text(strip_tags($text, '<br><p><b><i><li><ul><ol>'), $limit)
                         )
                     )
                )
            );

}

function shorten_text_link_characters($text, $limit = 300) 
{
   
    return  nl2br(
                str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), 
                     make_clickable_links(
                         htmlspecialchars(
                             _shorten_text_characters(strip_tags($text, '<br><p><b><i><li><ul><ol>'), $limit)
                         )
                     )
                )
            ) . '...';
}

function filter_api_text($text)
{
    return str_replace(array('  ', "\t"), array('&nbsp;&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'), make_clickable_links(htmlspecialchars(strip_tags($text, '<br><p><b><i><li><ul><ol>'))));
}

function filter_text($text)
{
    return nl2br(filter_api_text($text));
}

function make_clickable_links($text) {
    return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-\(\):~=,\-]*(\?[^"<>\s]+)?[^"<>\.\\s])?)?)@', '<a href="$1" rel="nofollow" style="word-break: break-all;" target="_blank">$1</a>', $text);
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

    $from = is_egora() ? 46 : 23 ;
    
    for($i=$from;$i>=1;$i--){
        $places[] = $i;
    }
    
    if (is_egora()) {
        for($i=-1;$i>=-5;$i--){
            $places[] = $i;
        }
    } else {
        for($i=-1;$i>=-6;$i--){
            $places[] = $i;
        }        
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

function bookmarks_has_place($ideas, $idea) {
    $max = ($idea->community ? $idea->community->bookmark_limit : 300);
    
    $down = $idea->pivot->order > 1;
    $up = $idea->pivot->order < $max;

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

function switch_by_idea($idea) {
    $egora = collect(config('egoras'))->first(function($value, $key) use ($idea) {
        return $value['id'] == $idea->egora_id;
    });

    request()->session()->put('current_egora', $egora['name']);
}

function switch_to_egora($egora = 'default') {
    request()->session()->put('current_egora', $egora);
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

function _bg_color($egora, $user) {
    if ($egora == 'default' 
        && ($user->user_type->class == 'user' || $user->user_type->former)
        ) {
        return "#636363";
    }
    
    return config(implode('.',['egoras',$egora,'bgcolor']));
}

function bg_color_by_egora_id($id) {
    $egora = collect(config('egoras'))->first(function($value, $key) use ($id) {
        return $value['id'] == $id;
    });

    if ($egora['name'] == 'default' 
        &&  auth('web')->check() 
        && auth('web')->user() 
        && (auth('web')->user()->user_type->class == 'user' || auth('web')->user()->user_type->former)
        ) {
        return "#636363";
    }

    return $egora['bgcolor'];
}

function no_html(string $input, string $encoding = 'UTF-8'): string
{
    return htmlentities($input, ENT_COMPAT | ENT_QUOTES , $encoding);
}

function negative_order($idea = null) : array
{
    // return array based on egora id
    if (!is_null($idea) && $idea->egora_id == 1) {
        return [-1=>'E',-2=>'G',-3=>'O',-4=>'R',-5=>'A'];
    } else if (!is_null($idea) && $idea->egora_id == 3) {
        return [-1=>'N',-2=>'O',-3=>'H',-4=>'A',-5=>'T', -6=>'E'];
    }
    
    return is_egora() ? [-1=>'E',-2=>'G',-3=>'O',-4=>'R',-5=>'A'] : [-1=>'N',-2=>'O',-3=>'H',-4=>'A',-5=>'T', -6=>'E'];
}

function array_nation_USA() : array
{
    return [
        'US',
        'USA',
        'USA, US, America, United States',
        'usa',
        'United  States',
        'United Staets',
        'United States',
        'United states',
        'United states of America',
        'united states',
        'UNITED STATES OF AMERICA'
    ];
}

function replace_nation_USA($prefix) : string
{
    $array = array_filter(array_nation_USA(), function($v) use ($prefix) {
        return strpos($v, $prefix) === 0;
    });
    
    if (!empty($array)) {
        return 'United States of America';
    }
    
    return '';
}

function communities_list() : array
{
    return [
        1=>"Egora development",
        2=>"Truths we all should know",
        3=>"Stories we all should hear",
        4=>"World's biggest challenges",
        5=>"Citizen Assembly",
        6=>"Shaping culture",
        7=>"Media we can trust",
        8=>"Businesses to boycott",
        9=>"Political vocabulary",
        10=>"My favorite books",
        11=>"My favorite films",
        12=>"My favorite recipes",
        13=>"My personal values",
        14=>"Relationship advice",
        15=>"Bucket list",
        16=>"Other public communities",
    ];
}

function _clean_search_name($search_name) {
    return str_replace(' ', '_', $search_name);
}

function _url_search_name($search_name) {
    return str_replace('_', ' ', $search_name);
}

function _url_replace($url, $clear=false) {
    $result = str_replace(['http://', 'https://', 'egora','ilp'], ['', '', 'Egora', 'ILP'], $url);
    
    if ($clear) {
        $result = substr_replace($result, '/ ', strrpos($result, '/'));
    }
    return $result;
}

function preview_id($id) {
    return base_convert($id, 10, 36);
}

function filter_office_hours_array($office_hours) {
    return array_filter($office_hours, function($value) { return !is_null($value['day']) && $value['day'] !== ''; });
}