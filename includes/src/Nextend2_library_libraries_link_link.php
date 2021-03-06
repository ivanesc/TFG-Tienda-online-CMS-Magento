<?php

class N2LinkParser
{

    public static function parse($url, &$attributes, $isEditor = false) {
        if ($url == '#' || $isEditor) {
            $attributes['onclick'] = "return false;";
        }

        preg_match('/^([a-zA-Z]+)\[(.*)]$/', $url, $matches);
        if (!empty($matches)) {
            $class = 'N2Link' . $matches[1];
            if (class_exists($class, false)) {
                $url = call_user_func_array(array(
                    $class,
                    'parse'
                ), array(
                    $matches[2],
                    &$attributes,
                    $isEditor
                ));
            }
        }
        return $url;
    }
}

class N2LinkScrollTo
{

    private static function init() {
        static $inited = false;
        if (!$inited) {
            N2JS::addInline('
            window.n2Scroll = {
                to: function(top){
                    n2("html, body").animate({ scrollTop: top }, 400);
                },
                top: function(){
                    n2Scroll.to(0);
                },
                bottom: function(){
                    n2Scroll.to(n2(document).height() - n2(window).height());
                },
                before: function(el){
                    n2Scroll.to(el.offset().top - n2(window).height());
                },
                after: function(el){
                    n2Scroll.to(el.offset().top + el.height());
                },
                next: function(el, selector){
                    var els = n2(selector),
                        nextI = -1;
                    els.each(function(i, slider){
                        if(n2(el).is(slider) || n2.contains(slider, el)){
                            nextI = i + 1;
                            return false;
                        }
                    });
                    if(nextI != -1 && nextI <= els.length){
                        n2Scroll.element(els.eq(nextI));
                    }
                },
                previous: function(el, selector){
                    var els = n2(selector),
                        prevI = -1;
                    els.each(function(i, slider){
                        if(n2(el).is(slider) || n2.contains(slider, el)){
                            prevI = i - 1;
                            return false;
                        }
                    });
                    if(prevI >= 0){
                        n2Scroll.element(els.eq(prevI));
                    }
                },
                element: function(selector){
                    n2Scroll.to(n2(selector).offset().top);
                }
            };');
            $inited = true;
        }
    }

    public static function parse($argument, &$attributes, $isEditor = false) {
        if (!$isEditor) {
            self::init();
            switch ($argument) {
                case 'top':
                case 'bottom':
                    $onclick = 'n2Scroll.' . $argument . '();';
                    break;
                case 'beforeSlider':
                    $onclick = 'n2Scroll.before(n2(this).closest(".n2-ss-slider").addBack());';
                    break;
                case 'afterSlider':
                    $onclick = 'n2Scroll.after(n2(this).closest(".n2-ss-slider").addBack());';
                    break;
                case 'nextSlider':
                    $onclick = 'n2Scroll.next(this, ".n2-ss-slider");';
                    break;
                case 'previousSlider':
                    $onclick = 'n2Scroll.previous(this, ".n2-ss-slider");';
                    break;
                default:
                    $onclick = 'n2Scroll.element("' . $argument . '");';
                    break;
            }
            $attributes['onclick'] = NHtml::encode($onclick . "return false;");
        }
        return '#';
    }
}